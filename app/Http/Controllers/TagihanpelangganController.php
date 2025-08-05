<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Notifications\BookingNotification;
use App\Models\User;
use App\Models\Kost;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\CoreApi;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSuccessMail;
use Midtrans\Transaction;

use Illuminate\Support\Facades\Http;
class TagihanpelangganController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

    public function index()
    {
        // Carbon::setTestNow(Carbon::parse('2026-1-3'));
        $userId = Auth::id();

        $bookings = Booking::with(['kost', 'user', 'tagihan'])
            ->where('user_id', $userId)
            ->get();

        return view('tagihanpelanggan.index', [
            'data' => $bookings,
            'title' => 'Tagihan Booking yang Bisa Dibayar',
        ]);
    }

    public function create($booking_id)
    {
        // Carbon::setTestNow(Carbon::parse('2025-11-4'));
        $booking = Booking::with('kost', 'user', 'tagihan')->findOrFail($booking_id);
        
        $lastTagihan = $booking->tagihan
            ->where('status_pembayaran', 'success')
            ->sortByDesc('tanggal_tagihan')
            ->first();

        $tglTerakhir = $lastTagihan
            ? \Carbon\Carbon::parse($lastTagihan->tanggal_tagihan)
            : \Carbon\Carbon::parse($booking->tgl_booking);

        $jatuhTempo = $tglTerakhir->copy()->addDays(30);
        $tenggang = $jatuhTempo->copy()->addDay();

        // $now = now();
        // if ($now->gt($tenggang)) {
        //     $status = 'expired';
        // } elseif ($now->gt($jatuhTempo)) {
        //     $status = 'masa_tenggang';
        // } else {
        //     $status = 'aktif';
        // }

        // if ($status !== 'masa_tenggang') {
        //     return back()->with('error', 'Hanya bisa membayar saat masa tenggang.');
        // }

        // Hitung sisa tagihan
        $hargaKost = $booking->kost->harga;
        $dpAwal = $booking->dp ?? 0;

        $totalSudahDibayar = Tagihan::where('booking_id', $booking->id)
            ->sum(DB::raw('bayar_per_bulan * jumlah_bayar'));

        $sisaTagihan = max(0, $hargaKost - $dpAwal - $totalSudahDibayar);

        $bulanKe = Tagihan::where('booking_id', $booking->id)->count() + 1;

        $tanggalTagihan = $lastTagihan
            ? \Carbon\Carbon::parse($lastTagihan->tanggal_tagihan)->addDays(30)->toDateString()
            : \Carbon\Carbon::parse($booking->tgl_booking)->addDays(30)->toDateString();

        $nominalBayar = $booking->kost->harga;

        // Generate payment data for direct QR display
        $paymentData = $this->generatePaymentData($booking, $nominalBayar, $bulanKe);

        return view('tagihanpelanggan.form', compact(
            'booking',
            'hargaKost',
            'dpAwal',
            'totalSudahDibayar',
            'sisaTagihan',
            'bulanKe',
            'tanggalTagihan',
            'nominalBayar',
            'paymentData'
        ));
    }

    

    private function generatePaymentData($booking, $amount, $month)
    {
        $orderId = 'TAGIHAN-' . $booking->id . '-' . time();
        
        $params = [
            'payment_type' => 'gopay',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? '08123456789',
            ],
            'item_details' => [
                [
                    'id' => $booking->id,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Pembayaran Bulanan Kost Bulan ke-' . $month,
                ]
            ],
            'gopay' => [
                'enable_callback' => true,
                'callback_url' => route('midtrans.callback')
            ]
        ];

        try {
            $response = CoreApi::charge($params);
            
            return [
                'success' => true,
                'order_id' => $orderId,
                'payment_data' => $response,
                'qr_code_url' => $response->actions[0]->url ?? null,
                'deeplink_url' => $response->actions[1]->url ?? null,
                'status_url' => $response->actions[2]->url ?? null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function callback(Request $request)
    {
        try {
            Log::info(' CALLBACK MASUK:', $request->all());

            $serverKey = env('MIDTRANS_SERVER_KEY');

            $validSignature = hash("sha512",
                $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey
            );

            if ($validSignature !== $request->signature_key) {
                Log::warning(' Signature tidak cocok!');
                return response()->json(['status' => 'forbidden'], 403);
            }

            $parts = explode('-', $request->order_id);
            $tagihanId = $parts[1] ?? null;

            if (!$tagihanId) {
                Log::error(' Gagal parse tagihan ID dari order_id!');
                return response()->json(['status' => 'invalid_order_id'], 400);
            }

            $tagihan = Tagihan::where('payment_token', $request->order_id)->first();
            if (!$tagihan) {
                Log::error(" Tagihan dengan order_id {$request->order_id} tidak ditemukan!");
                return response()->json(['status' => 'not_found'], 404);
            }

            $trxStatus = $request->transaction_status;
            $fraud = $request->fraud_status;

            $statusMap = match ($trxStatus) {
                'capture' => $fraud === 'challenge' ? 'challenge' : 'success',
                'settlement' => 'success',
                'pending' => 'pending',
                'deny' => 'failed',
                'expire' => 'expired',
                'cancel' => 'cancelled',
                default => 'unknown',
            };

            $tagihan->status_pembayaran = $statusMap;
            $tagihan->save();

            Log::info(" Tagihan ID {$tagihan->id} status diupdate ke: {$statusMap}");

            return response()->json(['status' => 'OK'], 200);
        } catch (\Throwable $e) {
            Log::error(" ERROR CALLBACK: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function checkPaymentStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required'
        ]);

        try {
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
            Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
            Config::$is3ds = env('MIDTRANS_IS_3DS');

            $status = \Midtrans\Transaction::status($request->order_id);
            
            return response()->json([
                'success' => true,
                'data' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
   public function updateStatus($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Update booking status to 'aktif'
        $booking->status = 'aktif';
        $booking->save();

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
{
    $request->validate([
        'booking_id' => 'required|exists:booking,id',
        'bayar_per_bulan' => 'required|integer|min:0',
        'jumlah_bayar' => 'required|integer|min:1',
        'order_id' => 'required'
    ]);

    $booking = Booking::where('id', $request->booking_id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    if (!in_array($booking->status, ['aktif', 'masa_tenggang'])) {
        return response()->json([
            'success' => false,
            'message' => 'Booking ini tidak bisa dibayar.'
        ], 400);
    }

    try {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');

        $statusResponse = \Midtrans\Transaction::status($request->order_id);
        
        $statusMap = match ($statusResponse->transaction_status) {
            'capture' => $statusResponse->fraud_status === 'challenge' ? 'challenge' : 'success',
            'settlement' => 'success',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'cancelled',
            default => 'unknown',
        };

        $tagihan = Tagihan::create([
            'booking_id' => $request->booking_id,
            'bayar_per_bulan' => $request->bayar_per_bulan,
            'jumlah_bayar' => $request->jumlah_bayar,
            'tanggal_tagihan' => Tagihan::where('booking_id', $request->booking_id)
                ->orderByDesc('tanggal_tagihan')
                ->first()
                ? Carbon::parse(
                    Tagihan::where('booking_id', $request->booking_id)
                        ->orderByDesc('tanggal_tagihan')
                        ->first()
                        ->tanggal_tagihan
                )->addDays(30)
                : Carbon::parse($booking->tgl_booking)->addDays(30),
            'status_pembayaran' => $statusMap,
            'payment_token' => $request->order_id,
            'keterangan' => $statusMap === 'success' ? 'paid' : 'unpaid'
        ]);

      if ($statusMap === 'success') {
    $booking->status = 'aktif';
    $booking->save();
}

$this->kirimWaBookingStatus(
    $booking->user->name,
    $booking->status_booking,
    $booking->kost->nomor_kamar,
    $booking->user->phone
);


        return response()->json([
            'success' => true,
            'redirect' => route('tagihanpelanggan.index')
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memverifikasi pembayaran: ' . $e->getMessage()
        ], 500);
    }
}
public function kirimWaBookingStatus($nama, $status, $nomorKamar, $nomorHP)
{
    $apiToken = env('JAPATI_API_TOKEN');
    $gateway = env('JAPATI_GATEWAY_NUMBER');

    $judul = match ($status) {
        'aktif' => '✅ Booking Aktif',
        'masa_tenggang' => '⚠️ Booking Masa Tenggang',
        'expired' => '❌ Booking Expired',
        default => 'ℹ️ Status Booking',
    };

    $isi = match ($status) {
        'aktif' => "Booking kamu untuk kamar $nomorKamar sudah aktif.",
        'masa_tenggang' => "Booking kamu masuk masa tenggang! Segera bayar ulang.",
        'expired' => "Booking kamu sudah expired! Hubungi admin.",
        default => 'Status booking tidak diketahui.',
    };

    $message = "*$judul*\n\n$isi";

    $res = Http::asForm()->withHeaders([
        'Authorization' => 'Bearer ' . $apiToken,
        'X-Requested-With' => 'XMLHttpRequest',
    ])->post('https://app.japati.id/api/send-message', [
        'gateway' => $gateway,
        'number' => $nomorHP,
        'type' => 'text',
        'message' => $message,
    ]);

    if (!$res->successful()) {
        Log::error('Gagal kirim WA booking: ' . $res->body());
    }
}



public function createCicilan($booking_id)
{
    $booking = Booking::with('kost', 'user', 'tagihan')->findOrFail($booking_id);
    
    // Check if booking belongs to authenticated user
    if ($booking->user_id != Auth::id()) {
        abort(403);
    }

    // Get remaining balances for each component
    $sisaKost = $booking->kost->harga - $booking->tagihan()->where('component', 'kost')->sum('amount_paid');
    $air = 100000; // Example value for water bill
    $sisaAir = $air - $booking->tagihan()->where('component', 'air')->sum('amount_paid');
    $kebersihan = 50000; // Example value for cleaning bill
    $sisaKebersihan = $kebersihan - $booking->tagihan()->where('component', 'kebersihan')->sum('amount_paid');

    $totalTagihan = $sisaKost + $sisaAir + $sisaKebersihan;

    return view('tagihanpelanggan.cicilan', compact(
        'booking',
        'sisaKost',
        'air',
        'sisaAir',
        'kebersihan',
        'sisaKebersihan',
        'totalTagihan'
    ));
}

public function processCicilan(Request $request)
{
    $request->validate([
        'booking_id' => 'required|exists:booking,id',
        'nominal_pembayaran' => 'required|numeric|min:1',
        'components' => 'required|array|min:1'
    ]);

    $booking = Booking::findOrFail($request->booking_id);
    $nominal = $request->nominal_pembayaran;
    $components = $request->components;

    // Generate order ID
    $orderId = 'CICILAN-' . $booking->id . '-' . time();

    // Prepare Midtrans payment
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
    Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
    Config::$is3ds = env('MIDTRANS_IS_3DS');

    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $nominal,
        ],
        'customer_details' => [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone ?? '08123456789',
        ],
        'item_details' => [
            [
                'id' => $booking->id,
                'price' => $nominal,
                'quantity' => 1,
                'name' => 'Cicilan Pembayaran Kost (' . implode(', ', $components) . ')',
            ]
        ],
        'callbacks' => [
            'finish' => route('cicilan.finish')
        ]
    ];

    try {
        $snapToken = Snap::getSnapToken($params);
        
        return response()->json([
            'success' => true,
            'order_id' => $orderId,
            'snap_token' => $snapToken,
            'message' => 'Token berhasil dibuat'
        ]);
    } catch (\Exception $e) {
        Log::error('Midtrans Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal membuat token pembayaran: ' . $e->getMessage()
        ], 500);
    }
}

// Add this new method to handle finish redirect
public function finishPayment(Request $request)
{
    // You can add any logic here for after payment completion
    return redirect()->route('tagihanpelanggan.index')->with('success', 'Pembayaran berhasil diproses');
}

public function checkCicilanStatus(Request $request)
{
    $request->validate([
        'order_id' => 'required'
    ]);

    try {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');

        $status = Transaction::status($request->order_id);
        
        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

// In your TagihanpelangganController, modify the storeCicilan method:

public function storeCicilan(Request $request)
{
    $request->validate([
        'booking_id' => 'required|exists:booking,id',
        'nominal_pembayaran' => 'required|numeric|min:1',
        'components' => 'required|array|min:1',
        'order_id' => 'required'
    ]);

    $booking = Booking::findOrFail($request->booking_id);
    $nominal = $request->nominal_pembayaran;
    $components = $request->components;
    $orderId = $request->order_id;

    try {
        $status = Transaction::status($orderId);
        
        $statusMap = match ($status->transaction_status) {
            'capture' => $status->fraud_status === 'challenge' ? 'challenge' : 'success',
            'settlement' => 'success',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'cancelled',
            default => 'unknown',
        };

        if ($statusMap !== 'success') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran belum berhasil'
            ], 400);
        }

        // Calculate amount per component
        // $componentCount = count($components);
        // $amountPerComponent = $nominal / $componentCount;

        // // Create installment records
        // foreach ($components as $component) {
        //     Tagihan::create([
        //         'booking_id' => $booking->id,
        //         'component' => $component,
        //         'amount_paid' => $amountPerComponent,
        //         'tanggal_tagihan' => now(),
        //         'status_pembayaran' => $statusMap,
        //         'payment_token' => $orderId,
        //         'keterangan' => 'cicilan'
        //     ]);
        // }

        // Ambil sisa tagihan per komponen yang dipilih
$sisaTagihanMap = [
    'kost' => $booking->kost->harga - $booking->tagihan()->where('component', 'kost')->sum('amount_paid'),
    'air' => 100000 - $booking->tagihan()->where('component', 'air')->sum('amount_paid'),
    'kebersihan' => 50000 - $booking->tagihan()->where('component', 'kebersihan')->sum('amount_paid'),
];

// Hitung total sisa tagihan dari komponen yang dipilih
$totalSisaDipilih = 0;
foreach ($components as $component) {
    $totalSisaDipilih += $sisaTagihanMap[$component] ?? 0;
}

// Proses dan simpan sesuai proporsi
foreach ($components as $component) {
    $sisa = $sisaTagihanMap[$component] ?? 0;

    if ($totalSisaDipilih > 0 && $sisa > 0) {
        $proportion = $sisa / $totalSisaDipilih;
        $amountPaid = round($proportion * $nominal); // atau floor() untuk pembulatan bawah

        Tagihan::create([
            'booking_id' => $booking->id,
            'component' => $component,
            'amount_paid' => $amountPaid,
            'tanggal_tagihan' => now(),
            'status_pembayaran' => $statusMap,
            'payment_token' => $orderId,
            'keterangan' => 'cicilan'
        ]);
    }
}


        return response()->json([
            'success' => true,
            'redirect' => route('tagihanpelanggan.index')
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan cicilan: ' . $e->getMessage()
        ], 500);
    }

    
    if (!$res->successful()) {
        Log::error('Gagal kirim WA booking: ' . $res->body());
    }
}


}


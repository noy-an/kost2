<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Deposit;
use App\Models\DepositHistory;
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

        $booking->updated_at = now();
        $booking->save();

        return response()->json(['success' => true]);
    }

 public function store(Request $request)
{
    $request->validate([
        'booking_id' => 'required|exists:booking,id',
        'jumlah_bayar' => 'required|integer|min:1',
        'order_id' => 'required'
    ]);

    $booking = Booking::where('id', $request->booking_id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $deposit = Deposit::firstOrCreate(
        ['user_id' => Auth::id()],
        ['amount' => 0]
    );

    $tagihanSekarang = Tagihan::where('booking_id', $booking->id)
        ->where('status_pembayaran', '!=', 'paid')
        ->orderBy('tgl_tagihan', 'asc')
        ->first();

    if (!$tagihanSekarang) {
        return back()->with('error', 'Tidak ada tagihan aktif.');
    }

    $nominalBayar = $request->jumlah_bayar;
    $sisaTagihan = $tagihanSekarang->total - $tagihanSekarang->jumlah_bayar;

    // ===== PROSES PEMBAYARAN =====
    if ($nominalBayar >= $sisaTagihan) {
        // Lunas
        $tagihanSekarang->jumlah_bayar += $sisaTagihan;
        $tagihanSekarang->status_pembayaran = 'paid';
        $tagihanSekarang->save();

        // Kurangi deposit
        $deposit->amount -= $sisaTagihan;
        $deposit->save();

        // Catat history keluar
        DepositHistory::create([
            'deposit_id' => $deposit->id,
            'name' => 'Pembayaran tagihan kost (lunas)',
            'amount' => $sisaTagihan,
            'type' => 'kredit'
        ]);

        $nominalBayar -= $sisaTagihan;

        // Buat tagihan bulan berikutnya
        $tagihanBaru = Tagihan::create([
            'booking_id' => $booking->id,
            'tgl_tagihan' => Carbon::parse($tagihanSekarang->tgl_tagihan)->addMonth(),
            'total' => $tagihanSekarang->total, // bisa disesuaikan
            'jumlah_bayar' => 0,
            'status_pembayaran' => 'unpaid'
        ]);

    } else {
        // Parsial
        $tagihanSekarang->jumlah_bayar += $nominalBayar;
        $tagihanSekarang->status_pembayaran = 'partial';
        $tagihanSekarang->save();

        // Kurangi deposit
        $deposit->amount -= $nominalBayar;
        $deposit->save();

        // Catat history keluar
        DepositHistory::create([
            'deposit_id' => $deposit->id,
            'name' => 'Pembayaran tagihan kost (parsial)',
            'amount' => $nominalBayar,
            'type' => 'kredit'
        ]);

        $nominalBayar = 0;
    }

    return back()->with('success', 'Pembayaran berhasil diproses.');
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
    
    if ($booking->user_id != Auth::id()) {
        abort(403);
    }

    $totalPaidKost = $booking->tagihan()->where('component', 'kost')->sum('amount_paid');
    $totalPaidAir = $booking->tagihan()->where('component', 'air')->sum('amount_paid');
    $totalPaidKebersihan = $booking->tagihan()->where('component', 'kebersihan')->sum('amount_paid');

    $hargaKost = $booking->kost->harga;
    $dpAwal = $booking->dp ?? 0;
    $air = $booking->kost->air ?? 100000;
    $kebersihan = $booking->kost->kebersihan ?? 50000;

    $sisaKost = max(0, $hargaKost - $dpAwal - $totalPaidKost);
    $sisaAir = max(0, $air - $totalPaidAir);
    $sisaKebersihan = max(0, $kebersihan - $totalPaidKebersihan);

    // Cek kalau semua komponen bulan ini sudah lunas
    if ($sisaKost == 0 && $sisaAir == 0 && $sisaKebersihan == 0) {
        // Ambil tagihan terakhir
        $tagihanTerakhir = $booking->tagihan()->orderByDesc('tanggal_tagihan')->first();

        // Kalau sudah lewat waktunya, set harga bulan berikutnya
        if ($tagihanTerakhir && \Carbon\Carbon::parse($tagihanTerakhir->tanggal_tagihan)->lte(now())) {
            $sisaKost = $hargaKost; 
            $sisaAir = $air;
            $sisaKebersihan = $kebersihan;
        }
    }

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
            'nominal_pembayaran' => 'required|numeric|min:5000',
            'components' => 'required|array|min:1'
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $nominal = $request->nominal_pembayaran;
        $components = $request->components;

        $orderId = 'CICILAN-' . $booking->id . '-' . time();

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

    public function finishPayment(Request $request)
    {
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
public function storeCicilan(Request $request)
{
    $request->validate([
        'booking_id' => 'required|exists:booking,id',
        'nominal_pembayaran' => 'required|numeric|min:5000',
        'components' => 'required|array|min:1',
        'order_id' => 'required'
    ]);

    $booking = Booking::findOrFail($request->booking_id);
    $nominal = $request->nominal_pembayaran;
    $components = $request->components;
    $orderId = $request->order_id;

    try {
        // ===== 1. CEK STATUS MIDTRANS =====
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

        // ===== 2. AMBIL BULAN TAGIHAN TERTUA YANG BELUM LUNAS =====
        $bulanTagihan = $booking->tagihan()
            ->selectRaw("DATE_FORMAT(tanggal_tagihan, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get()
            ->firstWhere(function ($row) use ($booking) {
                $tagihanBulan = $booking->tagihan()
                    ->whereRaw("DATE_FORMAT(tanggal_tagihan, '%Y-%m') = ?", [$row->bulan])
                    ->get();
                return !$tagihanBulan->every(fn($t) => $t->keterangan === 'paid');
            })->bulan ?? now()->format('Y-m');

        // ===== 3. HARGA PER KOMPONEN =====
        $harga = [
            'kost' => $booking->kost->harga,
            'air' => $booking->kost->air ?? 100000,
            'kebersihan' => $booking->kost->kebersihan ?? 50000,
        ];

        // Ambil semua tagihan bulan yang diproses
        $tagihanBulanIni = $booking->tagihan()
            ->whereRaw("DATE_FORMAT(tanggal_tagihan, '%Y-%m') = ?", [$bulanTagihan])
            ->get()
            ->groupBy('component');

        $totalPaid = [
            'kost' => $tagihanBulanIni->has('kost') ? $tagihanBulanIni['kost']->sum('amount_paid') : 0,
            'air' => $tagihanBulanIni->has('air') ? $tagihanBulanIni['air']->sum('amount_paid') : 0,
            'kebersihan' => $tagihanBulanIni->has('kebersihan') ? $tagihanBulanIni['kebersihan']->sum('amount_paid') : 0,
        ];

        // DP hanya bulan pertama booking
        $isFirstMonth = $booking->created_at->format('Y-m') === $bulanTagihan;
        $dpAwal = $isFirstMonth ? ($booking->dp ?? 0) : 0;

        // Sisa per komponen
        $sisaTagihan = [
            'kost' => max(0, $harga['kost'] - $dpAwal - $totalPaid['kost']),
            'air' => max(0, $harga['air'] - $totalPaid['air']),
            'kebersihan' => max(0, $harga['kebersihan'] - $totalPaid['kebersihan']),
        ];

        $totalSisaDipilih = array_sum(array_intersect_key($sisaTagihan, array_flip($components)));
        $amountPaidPerComponent = [];

        // ===== 4. SIMPAN PEMBAYARAN KE TAGIHAN =====
        foreach ($components as $component) {
            $sisa = $sisaTagihan[$component] ?? 0;
            if ($sisa <= 0) {
                $amountPaidPerComponent[$component] = 0;
                continue;
            }

            $proportion = $sisa / $totalSisaDipilih;
            $amountPaid = round($proportion * min($nominal, $totalSisaDipilih), 2);
            $amountPaid = min($amountPaid, $sisa);

            $amountPaidPerComponent[$component] = $amountPaid;

            $newTotalPaid = $totalPaid[$component] + $amountPaid + ($component === 'kost' ? $dpAwal : 0);
            $isLunas = $newTotalPaid >= $harga[$component] - 0.01;
            $keterangan = $isLunas ? 'paid' : 'cicilan';

            Tagihan::create([
                'booking_id' => $booking->id,
                'component' => $component,
                'amount_paid' => $amountPaid,
                'tanggal_tagihan' => now(),
                'status_pembayaran' => $statusMap,
                'payment_token' => $orderId,
                'keterangan' => $keterangan
            ]);
        }

        // ===== 5. SIMPAN KE DEPOSIT =====
        $deposit = Deposit::firstOrCreate(
            ['user_id' => $booking->user_id],
            ['amount' => 0]
        );
        $deposit->amount += $nominal;
        $deposit->save();

        DepositHistory::create([
            'deposit_id' => $deposit->id,
            'name' => 'Pembayaran ' . implode(', ', $components),
            'amount' => $nominal,
        ]);

        // ===== 6. CEK LUNAS ATAU BELUM =====
        $totalSisaBulanIni = [
            'kost' => max(0, $harga['kost'] - $dpAwal - ($totalPaid['kost'] + ($amountPaidPerComponent['kost'] ?? 0))),
            'air' => max(0, $harga['air'] - ($totalPaid['air'] + ($amountPaidPerComponent['air'] ?? 0))),
            'kebersihan' => max(0, $harga['kebersihan'] - ($totalPaid['kebersihan'] + ($amountPaidPerComponent['kebersihan'] ?? 0))),
        ];

        if (array_sum($totalSisaBulanIni) <= 0.01) {
            // Update booking ke aktif
            $booking->tgl_booking = now();
            $booking->status = 'aktif';
            $booking->save();

            // Buat tagihan bulan berikutnya
            $nextMonthDate = Carbon::createFromFormat('Y-m', $bulanTagihan)
                ->addMonthNoOverflow()
                ->startOfMonth();

            foreach (['kost', 'air', 'kebersihan'] as $comp) {
                Tagihan::firstOrCreate([
                    'booking_id' => $booking->id,
                    'component' => $comp,
                    'tanggal_tagihan' => $nextMonthDate,
                ], [
                    'amount_paid' => 0,
                    'status_pembayaran' => 'pending',
                    'keterangan' => 'unpaid'
                ]);
            }

            // Kirim WA
            $this->kirimWaBookingStatus(
                $booking->user->name,
                'aktif',
                $booking->kost->nomor_kamar,
                $booking->user->phone
            );
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
}

}
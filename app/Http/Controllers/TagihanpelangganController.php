<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Log;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class TagihanpelangganController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');
    }


public function index()
{
     //Carbon::setTestNow(Carbon::now()->addDays(30)); 

    $userId = Auth::id();

    $bookings = Booking::with(['kost', 'user', 'tagihan'])
        ->where('user_id', $userId)
        ->get()
        ->map(function ($booking) {
            $lastTagihan = $booking->tagihan->sortByDesc('tanggal_tagihan')->first();

            $tglTerakhir = $lastTagihan
                ? Carbon::parse($lastTagihan->tanggal_tagihan)
                : Carbon::parse($booking->tgl_booking);

            $jatuhTempo = $tglTerakhir->copy()->addDays(30);
            $tenggang = $jatuhTempo->copy()->addDay();

            if ($lastTagihan && $lastTagihan->status_pembayaran === 'success') {
                    $booking->status_booking = 'aktif';
                }


            if (now()->gt($tenggang)) {
                $booking->status_booking = 'expired';
            } elseif (now()->gt($jatuhTempo)) {
                $booking->status_booking = 'masa_tenggang';
            }

            $booking->save();
            return $booking;
        });

    return view('tagihanpelanggan.index', [
        'data' => $bookings,
        'title' => 'Tagihan Booking yang Bisa Dibayar',
    ]);
}

    



   public function create($booking_id)
{
    $booking = Booking::with('kost', 'user', 'tagihan')->findOrFail($booking_id);

        if ($booking->status_booking !== 'masa_tenggang') {
            return back()->with('error', 'Hanya bisa membayar saat masa tenggang.');
        }


    $hargaKost = $booking->kost->harga;
    $dpAwal = $booking->dp ?? 0;

    $totalSudahDibayar = Tagihan::where('booking_id', $booking->id)
        ->sum(DB::raw('bayar_per_bulan * jumlah_bayar'));

    $sisaTagihan = max(0, $hargaKost - $dpAwal - $totalSudahDibayar);

    
$bulanKe = Tagihan::where('booking_id', $booking->id)->count() + 1;

$lastTagihan = $booking->tagihan
    ->where('status_pembayaran', 'success')
    ->sortByDesc('tanggal_tagihan')
    ->first();

$tanggalTagihan = $lastTagihan
    ? \Carbon\Carbon::parse($lastTagihan->tanggal_tagihan)->addDays(30)->toDateString()
    : \Carbon\Carbon::parse($booking->tgl_booking)->addDays(30)->toDateString();

$nominalBayar = $booking->kost->harga;

return view('tagihanpelanggan.form', compact(
    'booking',
    'hargaKost',
    'dpAwal',
    'totalSudahDibayar',
    'sisaTagihan',
    'bulanKe',
    'tanggalTagihan',
    'nominalBayar'
));
}


    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:booking,id',
            'bayar_per_bulan' => 'required|integer|min:0',
            'jumlah_bayar' => 'required|integer|min:0',
            'tanggal_tagihan' => 'required|date',
        ]);

       
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        
        if (!in_array($booking->status_booking, ['aktif', 'masa_tenggang'])) {
            return back()->with('error', 'Booking ini tidak bisa dibayar.');
        }

      
        $tagihan = Tagihan::create([
            'booking_id' => $request->booking_id,
            'bayar_per_bulan' => $request->bayar_per_bulan,
            'jumlah_bayar' => $request->jumlah_bayar,
            'tanggal_tagihan' => $request->tanggal_tagihan,
            'status_pembayaran' => 'pending', 
        ]);

        
        $transaction_details = [
            'order_id' => 'TAGIHAN-' . $tagihan->id . '-' . time(),
            'gross_amount' => (int) $request->bayar_per_bulan,
        ];

        $customer_details = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone ?? '08123456789',
        ];

        $item_details = [
            [
                'id' => $tagihan->id,
                'price' => (int) $request->bayar_per_bulan,
                'quantity' => 1,
                'name' => 'Pembayaran Bulanan Kost Bulan ke-' . $tagihan->jumlah_bayar,
            ]
        ];

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
            'callbacks' => [
                'finish' => route('tagihanpelanggan.index'),
            ]
        ];

        try {
            $paymentUrl = Snap::createTransaction($params)->redirect_url;
            
          
            $tagihan->update([
                'payment_token' => $transaction_details['order_id'],
                'payment_url' => $paymentUrl,
            ]);

            return redirect($paymentUrl);

        } catch (\Exception $e) {
          
            $tagihan->delete();
            
            return redirect()->back()
                ->with('error', 'Pembayaran gagal: ' . $e->getMessage());
        }
    }


   

public function callback(Request $request)
{
    Log::info('🔥 CALLBACK DARI MIDTRANS:', $request->all());

    try {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        // ✅ INI FORMULA HASH YANG BENER BUAT WEBHOOK
        $signatureKey = hash("sha512",
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        // ❗ CEK KECOCOKAN SIGNATURE
        if ($signatureKey !== $request->signature_key) {
            Log::warning('❌ SIGNATURE TIDAK COCOK, CALLBACK DITOLAK');
            return response()->json(['status' => 'forbidden'], 403);
        }

        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        $tagihanId = explode('-', $orderId)[1] ?? null;

        if (!$tagihanId) {
            Log::error('💀 GAGAL PARSING TAGIHAN ID DARI ORDER_ID');
            return response()->json(['status' => 'invalid order id'], 400);
        }

        $tagihan = Tagihan::find($tagihanId);

        if (!$tagihan) {
            Log::error("💀 TAGIHAN ID $tagihanId TIDAK DITEMUKAN");
            return response()->json(['status' => 'tagihan not found'], 404);
        }

        // 🔄 UPDATE STATUS TAGIHAN SESUAI TRANSAKSI
        switch ($transactionStatus) {
            case 'capture':
                $status = $fraudStatus == 'challenge' ? 'challenge' : 'success';
                break;
            case 'settlement':
                $status = 'success';
                break;
            case 'pending':
                $status = 'pending';
                break;
            case 'deny':
                $status = 'failed';
                break;
            case 'expire':
                $status = 'expired';
                break;
            case 'cancel':
                $status = 'cancelled';
                break;
            default:
                $status = 'unknown';
                break;
        }

        $tagihan->update(['status_pembayaran' => $status]);
        Log::info("✅ TAGIHAN $tagihanId DIUPDATE KE STATUS: $status");

        // 🧠 UBAH STATUS BOOKING KALO BERHASIL
        if ($status === 'success') {
            $booking = Booking::find($tagihan->booking_id);
            if ($booking) {
                $booking->status_booking = 'aktif';
                $booking->save();
                Log::info("🚀 BOOKING ID {$booking->id} DISET KE AKTIF");
            }
        }

        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        Log::error('💥 ERROR CALLBACK MIDTRANS: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}
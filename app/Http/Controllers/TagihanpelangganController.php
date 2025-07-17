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

     Carbon::setTestNow(Carbon::parse('2025-12-2'));
    $userId = Auth::id();

    $bookings = Booking::with(['kost', 'user', 'tagihan'])
        ->where('user_id', $userId)
        ->get();

    // ✅ Cek status & kirim notif di sini
    foreach ($bookings as $booking) {
        $status = $this->hitungStatusBooking($booking);
        $this->kirimNotifBooking($booking, $status);
    }

    return view('tagihanpelanggan.index', [
        'data' => $bookings,
        'title' => 'Tagihan Booking yang Bisa Dibayar',
    ]);
}

    



   public function create($booking_id)
{

     Carbon::setTestNow(Carbon::parse('2025-12-2'));
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

   
    $now = now();
    if ($now->gt($tenggang)) {
        $status = 'expired';
    } elseif ($now->gt($jatuhTempo)) {
        $status = 'masa_tenggang';
    } else {
        $status = 'aktif';
    }
// dd([
//     'last_tagihan' => optional($lastTagihan)->tanggal_tagihan,
//     'tgl_booking' => $booking->tgl_booking,
//     'tglTerakhir' => $tglTerakhir,
//     'jatuhTempo' => $jatuhTempo,
//     'tenggang' => $tenggang,
//     'now' => $now,
//     'status' => $status,
// ]);

    if ($status !== 'masa_tenggang') {
        return back()->with('error', 'Hanya bisa membayar saat masa tenggang.');
    }

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

            'status_pembayaran' => 'success', 
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
    try {
        Log::info('🔥 CALLBACK MASUK:', $request->all());

        $serverKey = env('MIDTRANS_SERVER_KEY');

        $validSignature = hash("sha512",
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($validSignature !== $request->signature_key) {
            Log::warning('🚫 Signature tidak cocok!');
            return response()->json(['status' => 'forbidden'], 403);
        }

        $parts = explode('-', $request->order_id);
        $tagihanId = $parts[1] ?? null;

        if (!$tagihanId) {
            Log::error('❌ Gagal parse tagihan ID dari order_id!');
            return response()->json(['status' => 'invalid_order_id'], 400);
        }

        $tagihan = Tagihan::find($tagihanId);
        if (!$tagihan) {
            Log::error("❌ Tagihan ID {$tagihanId} gak ketemu!");
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

        Log::info("✅ Tagihan ID {$tagihanId} status diupdate ke: {$statusMap}");

        // Optional: log booking, tapi ga update status_booking lagi
        if ($statusMap === 'success') {
            $booking = Booking::find($tagihan->booking_id);
            if ($booking) {
                Log::info("📦 Booking ID {$booking->id} dibayar, status akan dihitung otomatis.");
            }
        }

        return response()->json(['status' => 'OK'], 200);
    } catch (\Throwable $e) {
        Log::error("💥 ERROR CALLBACK: " . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
}
private function hitungStatusBooking($booking)
{
    $lastTagihan = Tagihan::where('booking_id', $booking->id)
        ->where('status_pembayaran', 'success')
        ->latest('tanggal_tagihan')
        ->first();

    $tglTerakhir = $lastTagihan
        ? Carbon::parse($lastTagihan->tanggal_tagihan)
        : Carbon::parse($booking->tgl_booking);

    $jatuhTempo = $tglTerakhir->copy()->addDays(30);
    $tenggang = $jatuhTempo->copy()->addDay();
    $now = Carbon::now();

    if ($now->gt($tenggang)) {
        return 'expired';
    } elseif ($now->gt($jatuhTempo)) {
        return 'masa_tenggang';
    } else {
        return 'aktif';
    }
}

private function kirimNotifBooking($booking, $status)
{
    $user = $booking->user;
    $kost = $booking->kost;

    $pesan = match ($status) {
        'aktif' => "Booking Anda untuk kamar {$kost->nomor_kamar} telah aktif.",
        'masa_tenggang' => "Booking Anda masuk masa tenggang. Segera lakukan pembayaran ulang!",
        'expired' => "Booking Anda sudah kadaluarsa! Harap hubungi admin.",
    };

   Notification::create([
    'user_id' => $user->id,
    'booking_id' => $booking->id, 
    'message' => $pesan,
    'link' => route('tagihanpelanggan.index'),
]);


    if ($user) {
        $user->notify(new BookingNotification($status, $kost->nomor_kamar));
    }
}

    public function show(string $id)
    {
        $booking = Booking::with(['user', 'kost'])->findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    public function edit(string $id)
    {
        $booking = Booking::findOrFail($id);
        $users = User::all();
        $kosts = Kost::all();

        return view('booking.edit', compact('booking', 'users', 'kosts'));
    }

   

    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus.');
    }

}
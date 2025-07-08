<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tagihan;
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
        $bookings = Booking::with(['kost', 'user'])
            ->where('user_id', Auth::id())
            ->where('status_booking', 'disetujui')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tagihanpelanggan.index', [
            'data' => $bookings,
            'title' => 'Booking yang Disetujui.',
        ]);
    }

    public function indexApproved()
    {
        $bookings = Booking::with(['kost', 'user'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['disetujui', 'approved', 'confirm', 'accepted', 'setuju'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tagihanpelanggan.index', [
            'data' => $bookings,
            'title' => 'Booking yang Disetujui',
        ]);
    }

    public function create($booking_id)
    {
        $booking = Booking::with('kost', 'user')->findOrFail($booking_id);
        $hargaKost = $booking->kost->harga;
        $dpAwal = $booking->dp ?? 0;

        $totalSudahDibayar = Tagihan::where('booking_id', $booking->id)
            ->sum(DB::raw('bayar_per_bulan * jumlah_bayar'));

        $sisaTagihan = max(0, $hargaKost - $dpAwal - $totalSudahDibayar);

        $bulanKe = Tagihan::where('booking_id', $booking->id)->count() + 1;

        $nominalBayar = $bulanKe === 1
            ? $hargaKost - $dpAwal
            : $hargaKost;

        $tagihanTerakhir = Tagihan::where('booking_id', $booking->id)
            ->orderByDesc('tanggal_tagihan')
            ->first();

        $tanggalTagihan = $tagihanTerakhir
            ? \Carbon\Carbon::parse($tagihanTerakhir->tanggal_tagihan)->addMonth()->toDateString()
            : now()->toDateString(); 

        return view('tagihanpelanggan.form', [
            'booking' => $booking,
            'hargaKost' => $hargaKost,
            'dpAwal' => $dpAwal,
            'totalSudahDibayar' => $totalSudahDibayar,
            'sisaTagihan' => $sisaTagihan,
            'bulanKe' => $bulanKe,
            'title' => 'Tagihan Bulan',
            'nominalBayar' => $nominalBayar,
            'tanggalTagihan' => $tanggalTagihan, 
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:booking,id',
            'bayar_per_bulan' => 'required|integer|min:0',
            'jumlah_bayar' => 'required|integer|min:0',
            'tanggal_tagihan' => 'required|date',
        ]);

        // Simpan data tagihan terlebih dahulu
        $tagihan = Tagihan::create([
            'booking_id' => $request->booking_id,
            'bayar_per_bulan' => $request->bayar_per_bulan,
            'jumlah_bayar' => $request->jumlah_bayar,
            'tanggal_tagihan' => $request->tanggal_tagihan,
            'status_pembayaran' => 'pending', // Tambahkan status pembayaran
        ]);

        // Siapkan data untuk Midtrans
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
                'name' => 'Pembayaran Bulanan Kost Bulan ke-' . ($tagihan->jumlah_bayar + 1),
            ]
        ];

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
            'callbacks' => [
                'finish' => route('tagihanpelanggan.index', $request->booking_id),
            ]
        ];

        try {
            $paymentUrl = Snap::createTransaction($params)->redirect_url;
            
            // Update tagihan dengan payment token
            $tagihan->update([
                'payment_token' => $transaction_details['order_id'],
                'payment_url' => $paymentUrl,
            ]);

            return redirect($paymentUrl);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Pembayaran gagal: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class TagihanpelangganController extends Controller
{
 public function index()
{
    $bookings = Booking::with(['kost', 'user'])
        ->where('user_id', Auth::id())
        ->where('status_booking', 'disetujui')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('tagihanpelanggan.index', [
        'data' => $bookings,
        'title' => 'Booking yang Disetujui',
    ]);
}


    // Method dengan berbagai kemungkinan filter status
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

    // Jumlah tagihan yang sudah dibayar (akumulasi)
    $totalSudahDibayar = Tagihan::where('booking_id', $booking->id)
        ->sum(DB::raw('bayar_per_bulan * jumlah_bayar'));

    // Hitung sisa tagihan
  $sisaTagihan = $hargaKost - $dpAwal - $totalSudahDibayar;
$sisaTagihan = max(0, $sisaTagihan); // ⬅️ biar gak bisa minus


    // Hitung bulan ke berapa sekarang
    $bulanKe = Tagihan::where('booking_id', $booking->id)->count() + 1;

    // Nominal tagihan bulan ini
    $nominalBayar = $bulanKe === 1
        ? $hargaKost - $dpAwal // lunasi DP
        : $hargaKost; // bulan berikutnya full

    return view('tagihanpelanggan.form', [
        'booking' => $booking,
        'hargaKost' => $hargaKost,
        'dpAwal' => $dpAwal,
        'totalSudahDibayar' => $totalSudahDibayar,
        'sisaTagihan' => $sisaTagihan,
        'bulanKe' => $bulanKe,
         'title' => 'Tagihan Bulan',
        'nominalBayar' => $nominalBayar,
    ]);


}




    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:booking,id',
            'bayar_per_bulan' => 'required|integer|min:0',
            'jumlah_bayar' => 'required|integer|min:0',
        ]);

        Tagihan::create([
            'booking_id' => $request->booking_id,
            'bayar_per_bulan' => $request->bayar_per_bulan,
            'jumlah_bayar' => $request->jumlah_bayar,
        ]);

        return redirect()->route('tagihanpelanggan.create', $request->booking_id)->with('success', 'Tagihan berhasil ditambahkan.');
    }
}
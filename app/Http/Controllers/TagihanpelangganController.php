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
        'title' => 'Booking yang Disetujui.',
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

        Tagihan::create([
            'booking_id' => $request->booking_id,
            'bayar_per_bulan' => $request->bayar_per_bulan,
            'jumlah_bayar' => $request->jumlah_bayar,
            'tanggal_tagihan' => $request->tanggal_tagihan,
        ]);

        return redirect()->route('tagihanpelanggan.create', $request->booking_id)->with('success', 'Tagihan berhasil ditambahkan.');
    }
}
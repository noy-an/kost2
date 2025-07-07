<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class TagihanpelangganController extends Controller
{
    public function index()
    {
        // Sementara tampilkan semua booking tanpa filter status
        // Nanti bisa disesuaikan setelah tahu status yang benar
        $bookings = Booking::with(['kost', 'user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tagihanpelanggan.index', [
            'data' => $bookings,
            'title' => 'Booking Saya',
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
        $booking = Booking::findOrFail($booking_id);

        return view('tagihanpelanggan.form', [
            'booking' => $booking
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
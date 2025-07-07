<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Booking;

class TagihanpelangganController extends Controller
{
    public function create($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);

        return view('tagihan.form', [
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

        return redirect()->route('tagihan.create', $request->booking_id)->with('success', 'Tagihan berhasil ditambahkan.');
    }
}


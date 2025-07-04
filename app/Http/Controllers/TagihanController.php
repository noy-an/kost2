<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Booking;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    // Tampilkan daftar tagihan
    public function index()
    {
        $tagihans = Tagihan::with('booking.user', 'booking.kost')->paginate(10);
        return view('tagihan.index', compact('tagihans'));
    }

    // Tampilkan form tambah tagihan
    public function create()
    {
        $bookings = Booking::with('user', 'kost')->get();
        return view('tagihan.create', compact('bookings'));
    }

    // Simpan tagihan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'bayar_per_bulan' => 'required|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
        ]);

        Tagihan::create($validated);

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil ditambahkan.');
    }

    // Tampilkan form edit tagihan
    public function edit(string $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $bookings = Booking::with('user', 'kost')->get();

        return view('tagihan.edit', compact('tagihan', 'bookings'));
    }

    // Update data tagihan
    public function update(Request $request, string $id)
    {
        $tagihan = Tagihan::findOrFail($id);

        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'bayar_per_bulan' => 'required|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
        ]);

        $tagihan->update($validated);

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil diperbarui.');
    }

    // Hapus tagihan
    public function destroy(string $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->delete();

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dihapus.');
    }
}

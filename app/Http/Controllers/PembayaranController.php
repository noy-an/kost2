<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    
public function index(Request $request)
{
    $pembayarans = Pembayaran::with(['booking.user', 'booking.kost'])
        ->when($request->search, function ($query) use ($request) {
            $search = $request->search;
            $query->whereHas('booking.user', fn($q) =>
                $q->where('name', 'like', "%$search%")
            )->orWhere('metode_bayar', 'like', "%$search%")
             ->orWhere('tempat_bayar', 'like', "%$search%")
             ->orWhere('status_bayar', 'like', "%$search%");
        })
        ->latest()
        ->paginate(10);

    return view('pembayaran.index', compact('pembayarans'));
}

    // Tampilkan form tambah pembayaran
    public function create()
    {
        $bookings = Booking::with('user', 'kost')->get();
        return view('pembayaran.create', compact('bookings'));
    }

    // Simpan pembayaran baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:booking,id',
            'metode_bayar' => 'required|string|unique:pembayaran,metode_bayar',
            'status_bayar' => 'required|in:paid,unpaid',
            'no_rekening' => 'required|integer',
            'tempat_bayar' => 'required|string',
            'transaksi_id' => 'required|string',
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload file bukti bayar
        if ($request->hasFile('bukti_bayar')) {
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            $validated['bukti_bayar'] = $path;
        }

        Pembayaran::create($validated);

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    // Tampilkan detail pembayaran
    public function show(string $id)
    {
        $pembayaran = Pembayaran::with('booking.user', 'booking.kost')->findOrFail($id);
        return view('pembayaran.show', compact('pembayaran'));
    }

    // Tampilkan form edit pembayaran
    public function edit(string $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $bookings = Booking::with('user', 'kost')->get();

        return view('pembayaran.edit', compact('pembayaran', 'bookings'));
    }

    // Update data pembayaran
    public function update(Request $request, string $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $validated = $request->validate([
            'booking_id' => 'required|exists:booking,id',
            'metode_bayar' => 'required|string|unique:pembayaran,metode_bayar,' . $pembayaran->id,
            'status_bayar' => 'required|in:paid,unpaid',
            'no_rekening' => 'required|integer',
            'tempat_bayar' => 'required|string',
            'transaksi_id' => 'required|string',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Jika ada file bukti bayar baru, upload dan hapus yang lama
        if ($request->hasFile('bukti_bayar')) {
            if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
                Storage::disk('public')->delete($pembayaran->bukti_bayar);
            }
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            $validated['bukti_bayar'] = $path;
        }

        $pembayaran->update($validated);

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    // Hapus pembayaran
    public function destroy(string $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        // Hapus file bukti bayar dari storage
        if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
}

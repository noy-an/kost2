<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Booking;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function bulkDelete(Request $request)
{
    $ids = $request->input('tagihan_ids', []);

    if (empty($ids)) {
        return redirect()->route('tagihan.index')->with('error', 'Tidak ada tagihan yang dipilih.');
    }

    Tagihan::whereIn('id', $ids)->delete();

    return redirect()->route('tagihan.index')->with('success', 'Berhasil menghapus beberapa tagihan.');
}

   public function index(Request $request)
{
    $visited = session('visited_menus', []);
    if (!in_array('tagihan', $visited)) {
        $visited[] = 'tagihan';
        session(['visited_menus' => $visited]);
    }

    $tagihans = Tagihan::with(['booking.user', 'booking.kost'])
        ->when($request->search, function ($query) use ($request) {
            $query->whereHas('booking.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('booking.kost', function ($q) use ($request) {
                $q->where('nomor_kamar', 'like', '%' . $request->search . '%');
            });
        })
        ->when($request->dari && $request->sampai, function ($query) use ($request) {
            $query->whereBetween('tanggal_tagihan', [$request->dari, $request->sampai]);
        })
        ->latest('tanggal_tagihan')
        ->paginate(10);

    return view('tagihan.index', compact('tagihans'));
}

    public function create()
    {
        $bookings = Booking::with('user', 'kost')->get();
        return view('tagihan.create', compact('bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:booking,id',
            'bayar_per_bulan' => 'required|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
        ]);

        Tagihan::create($validated);
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $bookings = Booking::with('user', 'kost')->get();

        return view('tagihan.edit', compact('tagihan', 'bookings'));
    }

    public function update(Request $request, string $id)
    {
        $tagihan = Tagihan::findOrFail($id);

        $validated = $request->validate([
            'booking_id' => 'required|exists:booking,id',
            'bayar_per_bulan' => 'required|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
        ]);

        $tagihan->update($validated);
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->delete();
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dihapus.');
    }
}

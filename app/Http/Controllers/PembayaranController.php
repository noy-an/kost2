<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Tagihan;

class PembayaranController extends Controller
{
   public function index(Request $request)
{
    $filter = $request->get('filter', 'dp');

    $bookings = collect();
    $tagihans = collect();

    if ($filter === 'dp') {
        $bookings = Booking::with('kost', 'user')
            ->whereNotNull('dp')
            ->when($request->filled('dari') && $request->filled('sampai'), function ($query) use ($request) {
                $query->whereBetween('tgl_booking', [$request->dari, $request->sampai]);
            })
            ->when($request->filled('nama'), function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->nama . '%');
                });
            })
            ->orderByDesc('created_at')
            ->get();
    } elseif ($filter === 'tagihan') {
        $tagihans = Tagihan::with('booking.kost', 'booking.user')
            ->whereNotNull('jumlah_bayar')
            ->when($request->filled('dari') && $request->filled('sampai'), function ($query) use ($request) {
                $query->whereBetween('tanggal_bayar', [$request->dari, $request->sampai]);
            })
            ->when($request->filled('nama'), function ($query) use ($request) {
                $query->whereHas('booking.user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->nama . '%');
                });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    return view('pembayaran.index', [
        'filter' => $filter,
        'bookings' => $bookings,
        'tagihans' => $tagihans,
    ]);
}
}
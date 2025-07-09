<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
    use App\Models\Booking;
use App\Models\Kost;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $totalBooking = Booking::count();
    $totalDP = Booking::sum('dp');

    // Ambil input filter
    $searchKamar = $request->input('search_kamar');
    $tanggal = $request->input('tanggal');

    $data = Booking::with('kost')
        ->when($searchKamar, function ($query, $searchKamar) {
            $query->whereHas('kost', function ($q) use ($searchKamar) {
                $q->where('nomor_kamar', 'like', '%' . $searchKamar . '%');
            });
        })
        ->when($tanggal, function ($query, $tanggal) {
            $query->whereDate('tgl_booking', $tanggal);
        })
        ->latest()
        ->paginate(10)
        ->appends($request->only(['search_kamar', 'tanggal'])); // supaya filter tetap terjaga di paginate

    return view('laporan.index', compact('totalBooking', 'totalDP', 'data'), [
        'title' => 'Pemilik'
    ]);
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kost;
use App\Models\Booking;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Kontak;

class PemilikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pemilik.index',[
            'title' => 'Pemilik',
            'kostCount' => Kost::count(),
            'bookingCount' => Booking::count(),
            'tagihanCount' => Tagihan::count(),
            'pembayaranCount' => Pembayaran::count(),
            'userCount' => User::count(),
            'kontakCount' => Kontak::count(),
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

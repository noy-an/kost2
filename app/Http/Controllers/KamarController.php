<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kost;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Kost::query();

        if ($search) {
            $query->where('nomor_kamar', 'like', '%' . $search . '%');
        }

      $kosts = $query->paginate(9); 

        return view('kamar.index', [
            'title' => 'Daftar Kost',
            'kosts' => $kosts
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
    $kosts = Kost::findOrFail($id);
    $user = Auth::user();
    $sudahBooking = false;
    $bookingUserSendiri = false;

    $bookingAktif = Booking::where('kost_id', $kosts->id)
        ->whereHas('tagihan', function ($q) {
            $q->where('status_pembayaran', 'success');
        })
        ->latest('tgl_booking')
        ->first();

    if ($bookingAktif) {
        $lastTagihan = $bookingAktif->tagihan
            ->where('status_pembayaran', 'success')
            ->sortByDesc('tanggal_tagihan')
            ->first();

        $tglTerakhir = $lastTagihan
            ? \Carbon\Carbon::parse($lastTagihan->tanggal_tagihan)
            : \Carbon\Carbon::parse($bookingAktif->tgl_booking);

        $jatuhTempo = $tglTerakhir->copy()->addDays(30);
        $tenggang = $jatuhTempo->copy()->addDay();
        $now = now();

        // Masih dalam masa aktif
        if ($now->lte($tenggang)) {
            $sudahBooking = true;

            // Booking ini milik user yang login?
            if ($user && $bookingAktif->user_id === $user->id) {
                $bookingUserSendiri = true;
            }
        }
    }

    return view('kamar.show', compact('kosts', 'sudahBooking', 'bookingUserSendiri'))->with('title', 'Detail Kamar');
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
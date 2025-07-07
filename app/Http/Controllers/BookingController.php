<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Kost;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $bookings = \App\Models\Booking::with(['user', 'kost'])
        ->when($request->search, function ($query) use ($request) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('kost', function ($q) use ($request) {
                $q->where('nomor_kamar', 'like', '%' . $request->search . '%');
            });
        })
        ->latest()
        ->paginate(10);

    return view('booking.index', compact('bookings'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $kosts = Kost::all();

        return view('booking.create', compact('users', 'kosts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kost_id' => 'required|exists:kost,id',
            'dp' => 'nullable|numeric|min:0',
            'tgl_booking' => 'required|date',
            'status_booking' => 'required|in:pending,disetujui',
        ]);

        Booking::create($validated);

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::with(['user', 'kost'])->findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = Booking::findOrFail($id);
        $users = User::all();
        $kosts = Kost::all();

        return view('booking.edit', compact('booking', 'users', 'kosts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kost_id' => 'required|exists:kost,id',
            'dp' => 'nullable|numeric|min:0',
            'tgl_booking' => 'required|date',
            'status_booking' => 'required|in:pending,disetujui',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update($validated);

        return redirect()->route('booking.index')->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus.');
    }
}

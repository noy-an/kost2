<?php

namespace App\Http\Controllers;
use App\Models\Tagihan;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Kost;
use Carbon\Carbon;

use App\Notifications\BookingNotification;

use App\Models\Notifbook;

class BookingController extends Controller
{
        
public function bulkDelete(Request $request)
{
    $ids = $request->input('booking_ids', []);

    if (empty($ids)) {
        return redirect()->route('booking.index')->with('error', 'Tidak ada booking yang dipilih.');
    }

    $bookings = Booking::whereIn('id', $ids)->get();

    foreach ($bookings as $booking) {
        if ($booking->kost) {
            $booking->kost->status = 'kosong';
            $booking->kost->save();
        }

        $booking->delete();
    }

    return redirect()->route('booking.index')->with('success', 'Berhasil menghapus booking terpilih.');
}


    public function index(Request $request)
    {
          //Carbon::setTestNow(Carbon::parse('2025-12-2'));
        $visited = session('visited_menus', []);
        if (!in_array('booking', $visited)) {
            $visited[] = 'booking';
            session(['visited_menus' => $visited]);
        }

        $bookings = Booking::with(['user', 'kost', 'tagihan'])
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('user', fn($q) =>
                    $q->where('name', 'like', '%' . $request->search . '%')
                )->orWhereHas('kost', fn($q) =>
                    $q->where('nomor_kamar', 'like', '%' . $request->search . '%')
                );
            })
            ->when($request->dari && $request->sampai, function ($query) use ($request) {
                $query->whereBetween('tgl_booking', [$request->dari, $request->sampai]);
            })
            ->latest()
            ->paginate(10);

            


        return view('booking.index', compact('bookings'));
    }

    public function create()
    {
        $users = User::all();
        $kosts = Kost::all();

        return view('booking.create', compact('users', 'kosts'));
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'kost_id' => 'required|exists:kost,id',
        'jenis_pembayaran' => 'required|in:cash,transfer_bank,qris,debit_card',
        'tgl_booking' => 'required|date',
    ]);

    $kost = Kost::findOrFail($validated['kost_id']);
    $harga = $kost->harga;

    $booking = Booking::create([
        'user_id' => $validated['user_id'],
        'kost_id' => $validated['kost_id'],
        'jenis_pembayaran' => $validated['jenis_pembayaran'],
        'tgl_booking' => $validated['tgl_booking'],
        'dp' => $harga, // bayar full langsung
    ]);

    Notifbook::create([
        'booking_id' => $booking->id,
        'message' => 'Ada booking baru dari ' . ($booking->user->name ?? 'Unknown') . ' untuk kamar ' . ($booking->kost->nomor_kamar ?? 'Tidak Diketahui'),
    ]);

    $status = $this->hitungStatusBooking($booking);
    $this->kirimNotifBooking($booking, $status);

    return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat.');
}

public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'kost_id' => 'required|exists:kost,id',
        'jenis_pembayaran' => 'required|in:cash,transfer_bank,qris,debit_card',
        'tgl_booking' => 'required|date',
    ]);

    $kost = Kost::findOrFail($validated['kost_id']);
    $harga = $kost->harga;

    $booking = Booking::findOrFail($id);
    $booking->update([
        'user_id' => $validated['user_id'],
        'kost_id' => $validated['kost_id'],
        'jenis_pembayaran' => $validated['jenis_pembayaran'],
        'tgl_booking' => $validated['tgl_booking'],
        'dp' => $harga, // langsung bayar full
    ]);

    // 💥 Hitung status & notif
    $status = $this->hitungStatusBooking($booking);
    $this->kirimNotifBooking($booking, $status);

    return redirect()->route('booking.index')->with('success', 'Booking berhasil diperbarui.');
}


private function hitungStatusBooking($booking)
{
    $lastTagihan = Tagihan::where('booking_id', $booking->id)
        ->where('status_pembayaran', 'success')
        ->latest('tanggal_tagihan')
        ->first();

    $tglTerakhir = $lastTagihan
        ? Carbon::parse($lastTagihan->tanggal_tagihan)
        : Carbon::parse($booking->tgl_booking);

    $jatuhTempo = $tglTerakhir->copy()->addDays(30);
    $tenggang = $jatuhTempo->copy()->addDay();
    $now = Carbon::now();

    if ($now->gt($tenggang)) {
        return 'expired';
    } elseif ($now->gt($jatuhTempo)) {
        return 'masa_tenggang';
    } else {
        return 'aktif';
    }
}

private function kirimNotifBooking($booking, $status)
{
    $user = $booking->user;
    $kost = $booking->kost;

    $pesan = match ($status) {
        'aktif' => "Booking Anda untuk kamar {$kost->nomor_kamar} telah aktif.",
        'masa_tenggang' => "Booking Anda masuk masa tenggang. Segera lakukan pembayaran ulang!",
        'expired' => "Booking Anda sudah kadaluarsa! Harap hubungi admin.",
    };

   Notification::create([
    'user_id' => $user->id,
    'booking_id' => $booking->id, 
    'message' => $pesan,
    'link' => route('tagihanpelanggan.index'),
]);


    if ($user) {
        $user->notify(new BookingNotification($status, $kost->nomor_kamar));
    }
}

    public function show(string $id)
    {
        $booking = Booking::with(['user', 'kost'])->findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    public function edit(string $id)
    {
        $booking = Booking::findOrFail($id);
        $users = User::all();
        $kosts = Kost::all();

        return view('booking.edit', compact('booking', 'users', 'kosts'));
    }

   

    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus.');
    }
}

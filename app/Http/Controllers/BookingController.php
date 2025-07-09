<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Kost;

use App\Notifications\BookingDisetujuiNotification;

use App\Models\Notifbook;

class BookingController extends Controller
{
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);

        logger('booking->user_id = ' . $booking->user_id);

        $booking->status_booking = 'disetujui';
        $booking->save();

        if ($booking->user_id) {
            Notification::create([
                'user_id' => $booking->user_id,
                'message' => 'Booking Anda telah disetujui. Silakan cek tagihan.',
                'link' => route('tagihanpelanggan.index'),
            ]);

           

            logger('NOTIF CREATED!');
        } else {
            logger('GAGAL BUAT NOTIF: user_id NULL');
        }
$user = User::find($booking->user_id);
if ($user) {
    $user->notify(new BookingDisetujuiNotification());
}
        return redirect()->route('tagihanpelanggan.index')->with('success', 'Booking disetujui.');
    }



    

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('booking_ids', []);
        if (empty($ids)) {
            return redirect()->route('booking.index')->with('error', 'Tidak ada booking yang dipilih.');
        }

        Booking::whereIn('id', $ids)->delete();

        return redirect()->route('booking.index')->with('success', 'Berhasil menghapus booking terpilih.');
    }

    public function index(Request $request)
    {
        $visited = session('visited_menus', []);
        if (!in_array('booking', $visited)) {
            $visited[] = 'booking';
            session(['visited_menus' => $visited]);
        }

        $bookings = Booking::with(['user', 'kost'])
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
            'persentase_pembayaran' => 'required|in:30,40,50,100',
            'jenis_pembayaran' => 'required|in:cash,transfer_bank,qris,debit_card',
            'tgl_booking' => 'required|date',
            'status_booking' => 'required|in:pending,disetujui',
        ]);

        if ($request->status_booking === 'disetujui') {
            Notification::create([
                'user_id' => $request->user_id,
                'message' => 'Booking Anda telah disetujui. Silakan cek tagihan.',
                'link' => route('tagihanpelanggan.index'),
            ]);

          
        }


 
    $user = User::find($request->user_id);
    if ($user) {
        $user->notify(new BookingDisetujuiNotification());
    }


    $kost = Kost::findOrFail($validated['kost_id']);
    $dp_amount = ($validated['persentase_pembayaran'] / 100) * $kost->harga;

        $kost = Kost::findOrFail($validated['kost_id']);
        $dp_amount = ($validated['persentase_pembayaran'] / 100) * $kost->harga;


        $booking = Booking::create([
            'user_id' => $validated['user_id'],
            'kost_id' => $validated['kost_id'],
            'persentase_pembayaran' => $validated['persentase_pembayaran'],
            'jenis_pembayaran' => $validated['jenis_pembayaran'],
            'dp' => $dp_amount,
            'tgl_booking' => $validated['tgl_booking'],
            'status_booking' => $validated['status_booking'],
        ]);

        // ✅ Notifikasi untuk setiap booking baru (admin)
        Notifbook::create([
            'booking_id' => $booking->id,
            'message' => 'Ada booking baru masuk dari ' . ($booking->user->name ?? 'Unknown') . ' untuk kamar ' . ($booking->kost->nomor_kamar ?? 'Tidak Diketahui'),
        ]);

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat.');
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

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kost_id' => 'required|exists:kost,id',
            'persentase_pembayaran' => 'required|in:30,40,50,100',
            'jenis_pembayaran' => 'required|in:cash,transfer_bank,qris,debit_card',
            'tgl_booking' => 'required|date',
            'status_booking' => 'required|in:pending,disetujui',
        ]);






        $kost = Kost::findOrFail($validated['kost_id']);
        $dp_amount = ($validated['persentase_pembayaran'] / 100) * $kost->harga;

        $booking = Booking::findOrFail($id);
        $booking->update([
            'user_id' => $validated['user_id'],
            'kost_id' => $validated['kost_id'],
            'persentase_pembayaran' => $validated['persentase_pembayaran'],
            'jenis_pembayaran' => $validated['jenis_pembayaran'],
            'dp' => $dp_amount,
            'tgl_booking' => $validated['tgl_booking'],
            'status_booking' => $validated['status_booking'],
        ]);

        if ($request->status_booking === 'disetujui') {
            Notification::create([
                'user_id' => $request->user_id,
                'message' => 'Booking Anda telah disetujui. Silakan cek tagihan.',
                'link' => route('tagihanpelanggan.index'),
            ]);

            Notifbook::create([
                'booking_id' => $booking->id,
                'message' => 'Booking atas nama ' . ($booking->user->name ?? 'Unknown') . ' telah diperbarui dan disetujui.',
            ]);

                    // kirim email
            $user = User::find($request->user_id);
            if ($user) {
                $user->notify(new BookingDisetujuiNotification());
            }
        }

        return redirect()->route('booking.index')->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus.');
    }
}

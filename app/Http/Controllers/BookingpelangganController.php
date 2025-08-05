<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Kost;
use App\Models\Notifbook;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSuccessMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class BookingpelangganController extends Controller
{
    

    public function create($kost_id)
    {
        $kost = Kost::find($kost_id);

        if (!$kost) {
            return redirect()->route('kamar.index')->with('error', 'Kost tidak ditemukan.');
        }

        if ($kost->status !== 'kosong') {
            return redirect()->route('kamar.index')->with('error', 'Kost sudah tidak tersedia.');
        }

        $harga_calculations = [
            'bayar_penuh' => $kost->harga + $kost->air + $kost->kebersihan,
        ];

        $bookings = Booking::where('kost_id', $kost->id)->get();
        $disabledDates = [];

        foreach ($bookings as $booking) {
            for ($i = 0; $i < 30; $i++) {
                $date = \Carbon\Carbon::parse($booking->tgl_booking)->addDays($i)->format('Y-m-d');
                $disabledDates[] = $date;
            }
        }

        return view('bookingpelanggan.form', [
            'title' => 'Bookingpelanggan',
            'kost' => $kost,
            'harga_calculations' => $harga_calculations,
            'disabledDates' => $disabledDates,
        ]);
    }

function kirimWaBookingStatus($nama, $status, $nomorKamar, $nomorHP)
{
    $apiToken = env('JAPATI_API_TOKEN');
    $gateway = env('JAPATI_GATEWAY_NUMBER');

    $judul = match ($status) {
        'aktif' => '✅ Booking Aktif',
        'masa_tenggang' => '⚠️ Booking Masa Tenggang',
        'expired' => '❌ Booking Expired',
        default => 'ℹ️ Status Booking',
    };

    $isi = match ($status) {
        'aktif' => "Booking kamu untuk kamar $nomorKamar sudah aktif.",
        'masa_tenggang' => "Booking kamu masuk masa tenggang! Segera bayar ulang.",
        'expired' => "Booking kamu sudah expired! Hubungi admin.",
        default => 'Status booking tidak diketahui.',
    };

    $message = "*$judul*\n\n$isi";

    $res = Http::asForm()->withHeaders([
        'Authorization' => 'Bearer ' . $apiToken,
        'X-Requested-With' => 'XMLHttpRequest',
    ])->post('https://app.japati.id/api/send-message', [
        'gateway' => $gateway,
        'number' => $nomorHP,
        'type' => 'text',
        'message' => $message,
    ]);

    if (!$res->successful()) {
        Log::error('Gagal kirim WA booking: ' . $res->body());
    }
}

    
  public function store(Request $request)
{
    $request->validate([
        'kost_id' => 'required|exists:kost,id',
        'tgl_booking' => 'required|date',
        'nominal_bayar' => 'required|numeric|min:1000',
    ]);

    $kost = Kost::findOrFail($request->kost_id);
    $persentase = $request->persentase_pembayaran;

    if ($kost->status !== 'kosong') {
        return redirect()->route('bookingpelanggan.form', $kost->id)
            ->with('error', 'Maaf, kost sudah tidak tersedia.');
    }

    $nominal_pembayaran = $request->nominal_bayar;
    $jenis_pembayaran = 'bayar_penuh';

    $booking = Booking::create([
        'user_id' => Auth::id(),
        'kost_id' => $kost->id,
        'dp' => $nominal_pembayaran,
        'persentase_pembayaran' => $persentase,
        'jenis_pembayaran' => $jenis_pembayaran,
        'tgl_booking' => $request->tgl_booking,
        'status_booking' => 'aktif',
    ]);

    // Ubah status kamar jadi "terisi"
    $kost->update([
        'status' => 'terisi',
    ]);

    // Buat notifikasi ke admin
    Notifbook::create([
        'booking_id' => $booking->id,
        'message' => 'Booking baru dari pelanggan ' . Auth::user()->name . ' untuk kamar ' . $kost->nomor_kamar,
    ]);

    // Kirim WA
    $this->kirimWaBookingStatus(
        $booking->user->name,
        'aktif',
        $booking->kost->nomor_kamar,
        $booking->user->phone
    );

    // Redirect dengan sukses
    return redirect()->route('tagihanpelanggan.index')->with('success', 'Booking berhasil dibuat tanpa pembayaran online.');
}

}
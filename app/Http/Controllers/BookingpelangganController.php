<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Deposit;
use App\Models\DepositHistory;
use App\Models\Kost;
use App\Models\Notifbook;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSuccessMail;

class BookingpelangganController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

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
            'bayar_penuh' => round($kost->harga),
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

    public function store(Request $request)
{
    $request->validate([
        'kost_id' => 'required|exists:kost,id',
        'tgl_booking' => 'required|date',
        'nominal_bayar' => 'required|numeric|min:1000',
    ]);

    $kost = Kost::findOrFail($request->kost_id);

    if ($kost->status !== 'kosong') {
        return redirect()->route('bookingpelanggan.form', $kost->id)
            ->with('error', 'Maaf, kost sudah tidak tersedia.');
    }

    // Hitung total harga
    $total_harga = $kost->harga + $kost->air + $kost->kebersihan; 
    $nominal_pembayaran = $request->nominal_bayar;
    $selisih = $nominal_pembayaran - $total_harga; // bisa negatif

    // Simpan booking
    $booking = Booking::create([
        'user_id' => Auth::id(),
        'kost_id' => $kost->id,
        'dp' => $nominal_pembayaran,
        'persentase_pembayaran' => $request->persentase_pembayaran ?? null,
        'jenis_pembayaran' => ($nominal_pembayaran >= $total_harga) ? 'bayar_penuh' : 'dp',
        'tgl_booking' => $request->tgl_booking,
        'status_booking' => 'aktif',
    ]);

    // Update status kamar
    $kost->update([
        'status' => 'terisi',
    ]);

    // Simpan notifikasi
    Notifbook::create([
        'booking_id' => $booking->id,
        'message' => 'Booking baru dari pelanggan ' . Auth::user()->name . ' untuk kamar ' . $kost->nomor_kamar,
    ]);

        $deposit = Deposit::where('user_id', Auth::id())->first();
    if ($deposit) {
        $deposit->amount += $selisih; 
        $deposit->save();


    DepositHistory::create([
        'deposit_id' => $deposit->id,
        'name'       => 'Booking Kamar Kost ' . $request->kost_id,
        'amount'     => $selisih,
    ]);
    }


    // Midtrans payment setup
    $transaction_details = [
        'order_id' => 'BOOKING-' . $booking->id . '-' . time(),
        'gross_amount' => (int) $nominal_pembayaran,
    ];

    $customer_details = [
        'first_name' => Auth::user()->name,
        'email' => Auth::user()->email,
        'phone' => Auth::user()->phone ?? '08123456789',
    ];

    $item_details = [
        [
            'id' => $kost->id,
            'price' => (int) $nominal_pembayaran,
            'quantity' => 1,
            'name' => 'Booking Kamar ' . $kost->nomor_kamar . ' (Lantai ' . $kost->nomor_lantai . ')',
        ]
    ];

    $params = [
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details,
        'item_details' => $item_details,
        'callbacks' => [
            'finish' => route('tagihanpelanggan.index'),
        ],
        'enabled_payments' => ['gopay'],
    ];

    try {
        $paymentUrl = Snap::createTransaction($params)->redirect_url;

        $booking->update([
            'payment_token' => $params['transaction_details']['order_id'],
            'payment_url' => $paymentUrl,
        ]);

        return redirect($paymentUrl);
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Pembayaran gagal: ' . $e->getMessage());
    }
}

}
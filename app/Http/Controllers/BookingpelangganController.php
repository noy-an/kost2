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

        Notifbook::create([
            'booking_id' => $booking->id,
            'message' => 'Booking baru dari pelanggan ' . Auth::user()->name . ' untuk kamar ' . $kost->nomor_kamar,
        ]);

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
        ];

        // === Bagian Custom Opsi Pembayaran Midtrans ===
        $params['enabled_payments'] = [      // 
            // 'qris',
            'gopay',
                  // 
            // 'credit_card' // 
        ];
        // === Selesai bagian custom ===

        try {
            $paymentUrl = Snap::createTransaction($params)->redirect_url;

            $booking->update([
                'payment_token' => $params['transaction_details']['order_id'],
                'payment_url' => $paymentUrl,
            ]);

            // KIRIM EMAIL BOOKING SUKSES
Mail::to(Auth::user()->email);


            return redirect($paymentUrl);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Pembayaran gagal: ' . $e->getMessage());
        }
    }
}

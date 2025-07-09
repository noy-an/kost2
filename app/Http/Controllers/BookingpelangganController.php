<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Kost;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class BookingpelangganController extends Controller
{

// Contoh di BookingController
public function approve($id)
{
    $booking = Booking::findOrFail($id);
    $booking->status_booking = 'disetujui';
    $booking->save();

    // Set flash notifikasi
    session()->flash('notif_booking', 'Booking Anda telah disetujui. Silakan cek tagihan.');

    return redirect()->route('bookingpelanggan.index')->with('success', 'Booking disetujui.');
}

    
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
            'dp_30' => round($kost->harga * 0.3),
            'dp_40' => round($kost->harga * 0.4),
            'dp_50' => round($kost->harga * 0.5),
            'bayar_penuh' => round($kost->harga),
        ];
        
        return view('bookingpelanggan.form', [
            'title' => 'Bookingpelanggan',
            'kost' => $kost,
            'harga_calculations' => $harga_calculations,
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'kost_id' => 'required|exists:kost,id',
            'tgl_booking' => 'required|date',
            'persentase_pembayaran' => 'required|in:30,40,50,100',
        ]);
        
        $kost = Kost::findOrFail($request->kost_id);
        $persentase = $request->persentase_pembayaran;
        
        if ($kost->status !== 'kosong') {
            return redirect()->route('bookingpelanggan.form', $kost->id)
                ->with('error', 'Maaf, kost sudah tidak tersedia.');
        }
        
        $nominal_pembayaran = round($kost->harga * ($persentase / 100));
        $jenis_pembayaran = $persentase == 100 ? 'bayar_penuh' : 'dp';
        
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'kost_id' => $kost->id,
            'dp' => $nominal_pembayaran,
            'persentase_pembayaran' => $persentase,
            'jenis_pembayaran' => $jenis_pembayaran,
            'tgl_booking' => $request->tgl_booking,
            'status_booking' => 'pending',
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
            ]
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
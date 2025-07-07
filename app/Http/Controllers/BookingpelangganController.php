<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Kost;
use Illuminate\Support\Facades\Auth;

class BookingpelangganController extends Controller
{
    public function create(Request $request)
    {
        $kost_id = $request->input('kost_id');
        
       
        
        // Find the kost or fail
        $kost = Kost::find($kost_id);
        
       
        
        // Hitung nominal untuk setiap persentase
        $nominal_pembayaran = [
            '30' => $kost->harga * 0.3,
            '40' => $kost->harga * 0.4,
            '50' => $kost->harga * 0.5,
            '100' => $kost->harga
        ];
        
        return view('bookingpelanggan.form', [
            'title' => 'Bookingpelanggan',
            'kost' => $kost,
            'nominal_pembayaran' => $nominal_pembayaran,
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
        
        // Hitung nominal pembayaran berdasarkan persentase
        $nominal_pembayaran = $kost->harga * ($persentase / 100);
        
        // Tentukan status dan jenis pembayaran
        $jenis_pembayaran = $persentase == 100 ? 'bayar_penuh' : 'dp';
        $status_booking = 'pending';
        
        Booking::create([
            'user_id' => Auth::id(),
            'kost_id' => $kost->id,
            'dp' => $nominal_pembayaran,
            'persentase_pembayaran' => $persentase,
            'jenis_pembayaran' => $jenis_pembayaran,
            'tgl_booking' => $request->tgl_booking,
            'status_booking' => $status_booking,
        ]);
        
        $pesan = $persentase == 100 
            ? 'Booking dengan pembayaran penuh berhasil dikirim!' 
            : "Booking dengan DP {$persentase}% berhasil dikirim!";
        
        return redirect()->route('home.index')->with('success', $pesan);
    }
}
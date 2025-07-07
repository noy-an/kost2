<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Kost;
use Illuminate\Support\Facades\Auth;

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
            'dp_30' => $kost->harga * 0.3,
            'dp_40' => $kost->harga * 0.4,
            'dp_50' => $kost->harga * 0.5,
            'bayar_penuh' => $kost->harga,
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
        
     
        $nominal_pembayaran = $kost->harga * ($persentase / 100);
        
       
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
        
       
        return redirect()->route('bookingpelanggan.form', $kost->id)->with('success', $pesan);
    }
}
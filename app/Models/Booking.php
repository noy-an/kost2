<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
        'user_id',
        'kost_id',
        'dp',
        'jenis_pembayaran',
        'tgl_booking',
        'payment_token',
        'payment_url',
    ];

    protected $dates = ['tgl_booking', 'created_at', 'updated_at'];

    // RELASI
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kost()
    {
        return $this->belongsTo(Kost::class, 'kost_id', 'id');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'booking_id');
    }
     public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

 public function getStatusSewaAttribute()
{
    $tglBooking = Carbon::parse($this->tgl_booking);
    $batasAktif = $tglBooking->copy()->addDays(30);
    $batasTenggang = $tglBooking->copy()->addDays(32);
    $now = now();

    // Hitung total tagihan dalam periode aktif
    $tagihan = $this->tagihan()
        ->whereDate('tanggal_tagihan', '>=', $tglBooking)
        ->whereDate('tanggal_tagihan', '<=', $batasAktif)
        ->get();

    $totalTagihan = $tagihan->sum('jumlah_tagihan');

    $totalPembayaran = $tagihan->flatMap->pembayaran
        ->where('status', 'success')
        ->sum('jumlah_bayar');

    // Status utama tetap berdasarkan umur sewa
    if ($now->lte($batasAktif)) {
        return 'aktif';
    }

    if ($now->gt($batasAktif) && $now->lte($batasTenggang)) {
        return 'masa_tenggang';
    }

    return 'expired';
}

public function getHariSejakBookingAttribute()
{
    return now()->diffInDays($this->tanggal_booking);
}

}

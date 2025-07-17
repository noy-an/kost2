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

    // 🔥 ACCESSOR STATUS DINAMIS (aktif / masa_tenggang / expired)
    public function getStatusBookingAttribute()
    {
        $lastTagihan = $this->tagihan()
            ->where('status_pembayaran', 'success')
            ->orderByDesc('tanggal_tagihan')
            ->first();

        $tanggalMulai = $lastTagihan
            ? Carbon::parse($lastTagihan->tanggal_tagihan)
            : Carbon::parse($this->tgl_booking);

        $jatuhTempo = $tanggalMulai->copy()->addDays(30);
        $tenggang = $jatuhTempo->copy()->addDay();
        $now = now();

        return match (true) {
            $now->gt($tenggang) => 'expired',
            $now->gt($jatuhTempo) => 'masa_tenggang',
            default => 'aktif',
        };
    }
}

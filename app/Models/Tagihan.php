<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Symfony\Component\Mime\Address;
class Tagihan extends Model
{
    protected $table = 'tagihan';

    protected $fillable = [
        'booking_id',
        'bayar_per_bulan',
        'jumlah_bayar',
        'tanggal_tagihan',
    ];


    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }
public function getNamaBulanDinamisAttribute()
{
    if (!$this->tanggal_tagihan || !$this->booking || !$this->booking->tgl_booking) {
        return '-';
    }

    try {
        $tglAwal = Carbon::parse($this->booking->tgl_booking)->startOfMonth();
        $tglTagihan = Carbon::parse($this->tanggal_tagihan)->startOfMonth();

        $bulanKe = $tglAwal->diffInMonths($tglTagihan) + 1;

        return $tglTagihan->translatedFormat('F Y') . " (Bulan ke-$bulanKe)";
    } catch (\Exception $e) {
        return '-';
    }
}


    public function getTanggalTagihanFormatAttribute()
    {
        if (!$this->tanggal_tagihan) return '-';

        return Carbon::parse($this->tanggal_tagihan)->translatedFormat('d F Y'); 
    }
}

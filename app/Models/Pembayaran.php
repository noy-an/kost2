<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'booking_id',
        'metode_bayar',
        'status_bayar',
        'no_rekening',
        'tempat_bayar',
        'transaksi_id',
        'bukti_bayar',
        'tanggal_bayar',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

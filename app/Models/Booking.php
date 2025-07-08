<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
        'user_id',
        'kost_id',
        'dp',
        'persentase_pembayaran',
        'jenis_pembayaran',
        'tgl_booking',
        'status_booking',
        'payment_token',
        'payment_url',
    ];

    protected $dates = ['tgl_booking', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kost()
    {
        return $this->belongsTo(Kost::class, 'kost_id', 'id');
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'booking_id', 'id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    protected $table = 'kost';

    protected $fillable = [
        'nomor_kamar',
        'nomor_lantai',
        'harga',
        'deskripsi',
        'status',
        'fasilitas',
        'foto1',
        'foto2',
        'foto3',
    ];

      public function booking()
    {
        return $this->hasOne(Booking::class, 'kost_id', 'id' );
    }

}

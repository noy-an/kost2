<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balasan extends Model
{
    protected $table = 'balasan';

    protected $fillable = [
        'kontak_id',
        'subjek',
        'pesan'
    ];

    public function kontak()
    {
        return $this->belongsTo(Kontak::class);
    }
}

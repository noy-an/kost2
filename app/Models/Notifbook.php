<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifbook extends Model
{
    protected $table = 'notifbook';

    protected $fillable = [
        'booking_id',
        'message',
        'is_read',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

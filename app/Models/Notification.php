<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
     protected $fillable = [
        'user_id',
        'message',
        'link',
        'is_read',
    ];

    // relasi biar bisa akses user-nya (opsional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

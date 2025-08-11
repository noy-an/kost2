<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositHistory extends Model
{
    use HasFactory;

    protected $table = 'deposit_history';

    protected $fillable = [
        'deposit_id',
        'name',
        'amount',
    ];

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }
}

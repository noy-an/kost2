<?php

use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\CekStatusBooking;

Artisan::command('cek:booking-status', function () {
    $this->call(CekStatusBooking::class);
})->purpose('Jalankan pengecekan status booking');


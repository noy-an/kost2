<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Kost;
use App\Models\Booking;
use App\Models\Tagihan;

use Illuminate\Support\Facades\Schedule;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Kontak;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $visited = Session::get('visited_menus', []);
            $notif = [
                'kostBaru' => Kost::whereDate('created_at', today())->exists() && !in_array('kost', $visited),
                'bookingBaru' => Booking::whereDate('created_at', today())->exists() && !in_array('booking', $visited),
                'tagihanBaru' => Tagihan::whereDate('created_at', today())->exists() && !in_array('tagihan', $visited),
                'pembayaranBaru' => Pembayaran::whereDate('created_at', today())->exists() && !in_array('pembayaran', $visited),
                'penggunaBaru' => User::whereDate('created_at', today())->exists() && !in_array('pengguna', $visited),
                'kontakBaru' => Kontak::whereDate('created_at', today())->exists() && !in_array('kontak', $visited),
            ];
  $this->app->booted(function () {
        Schedule::command('booking:cek-notif')->everyMinute();
    });
            $view->with($notif);
        });
    }
}

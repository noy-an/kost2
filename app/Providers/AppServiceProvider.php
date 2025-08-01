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
    // FUNCTION KIRIM WA - TARO DI SINI GOBLOK!
    if (!function_exists('kirimWa')) {
        function kirimWa($target, $pesan)
        {
            \Illuminate\Support\Facades\Http::asForm()->withHeaders([
                'Authorization' => 'Bearer ' . env('JAPATI_API_TOKEN'),
            ])->post('https://app.japati.id/api/send-message', [
                'gateway' => env('JAPATI_GATEWAY_NUMBER'),
                'number' => $target,
                'type' => 'text',
                'message' => $pesan,
            ]);
        }
    }

    // VIEW COMPOSER — INI BIARIN AJA
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

        $view->with($notif);
    });

    // SCHEDULE - BIARIN AJA
    $this->app->booted(function () {
        \Illuminate\Support\Facades\Schedule::command('booking:cek-notif')->everyMinute();
    });
}

}

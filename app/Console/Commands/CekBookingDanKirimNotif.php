<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Notifications\BookingNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class CekBookingDanKirimNotif extends Command
{
    protected $signature = 'booking:cek-notif';
    protected $description = 'Cek status booking dan kirim notifikasi email';

    public function handle()
    {
       Log::info('🔥 CekBookingDanKirimNotif JALAN jam: ' . now());
  //Carbon::setTestNow(Carbon::parse('2026-1-3'));

        $bookings = Booking::with(['user', 'kost', 'tagihan'])->get();

        foreach ($bookings as $booking) {
            if (!$booking->user) continue;

            $lastTagihan = $booking->tagihan
                ->where('status_pembayaran', 'success')
                ->sortByDesc('tanggal_tagihan')
                ->first();

            $tglTerakhir = $lastTagihan
                ? Carbon::parse($lastTagihan->tanggal_tagihan)
                : Carbon::parse($booking->tgl_booking);

            $jatuhTempo = $tglTerakhir->copy()->addDays(30);
            $tenggang = $jatuhTempo->copy()->addDay();
            $now = now();

            if ($now->gt($tenggang)) {
                $status = 'expired';
            } elseif ($now->gt($jatuhTempo)) {
                $status = 'masa_tenggang';
            } else {
                $status = 'aktif';
            }

            if (!$booking->notif_last_sent || $booking->notif_last_sent->lt(now()->subHours(12))) {
                $booking->user->notify(new BookingNotification($status, $booking->kost->nomor_kamar));
                $booking->update(['notif_last_sent' => now()]);
            }
        }

        $this->info('📬 Notifikasi booking selesai dikirim.');
    }
}

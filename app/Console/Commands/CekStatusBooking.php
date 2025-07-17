<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Carbon;

class CekStatusBooking extends Command
{
    protected $signature = 'cek:booking-status';
    protected $description = 'Cek status booking dan tandai expire jika sudah lewat 30 hari + 1';

    public function handle()
    {
        $today = Carbon::today();

        $bookings = Booking::whereDate('tgl_booking', '<', $today->copy()->subDays(30))
            ->where('status_booking', 'aktif') // atau apapun status aktif lu
            ->get();

        foreach ($bookings as $booking) {
            $expiredDate = Carbon::parse($booking->tgl_booking)->addDays(31); // 30 hari + 1 hari tenggat

            if ($today->greaterThan($expiredDate)) {
                $booking->status_booking = 'expire';
                $booking->save();
                $this->info("Booking ID {$booking->id} expired.");
            }
        }

        return Command::SUCCESS;
    }
}

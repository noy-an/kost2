<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CekBookingDanKirimNotif extends Command
{
    protected $signature = 'booking:cek-notif';
    protected $description = 'Cek status booking dan kirim notifikasi via WhatsApp';

   public function handle()
{
        // Simulasi tanggal (hapus kalau udah produksi)
        Carbon::setTestNow(Carbon::parse('2025-9-17'));
    Log::info('🔥 CekBookingDanKirimNotif JALAN jam: ' . now());

    $bookings = Booking::with(['user', 'kost', 'tagihan'])->get();

    foreach ($bookings as $booking) {
        if (!$booking->user || !$booking->user->phone) continue;

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

        $tagihanBulanIni = $booking->tagihan
            ->where('tanggal_tagihan', '>=', $tglTerakhir->copy()->addDays(30)->startOfDay())
            ->where('tanggal_tagihan', '<=', $tglTerakhir->copy()->addDays(30)->endOfDay())
            ->first();

        if ($tagihanBulanIni && $tagihanBulanIni->status_pembayaran === 'success') {
            $status = 'paid';
        } elseif ($now->lte($tenggang)) {
            $status = 'unpaid';
        } elseif ($now->gt($tenggang)) {
            $status = 'expired';
        } elseif ($now->gt($jatuhTempo)) {
            $status = 'masa_tenggang';
        } else {
            $status = 'aktif';
        }

        if (!$booking->notif_last_sent || $booking->notif_last_sent->lt(now()->subHours(12))) {
            $this->kirimWaNotif(
                $booking->user->name,
                $status,
                $booking->kost->nomor_kamar,
                $booking->user->phone
            );

            $booking->update(['notif_last_sent' => now()]);
        }
    }

    $this->info('📬 WhatsApp booking notif selesai dikirim.');
}


 private function kirimWaNotif($nama, $status, $nomorKamar, $nomorHP)
{

        // Simulasi tanggal (hapus kalau udah produksi)
        Carbon::setTestNow(Carbon::parse('2025-9-17'));
    $apiToken = env('JAPATI_API_TOKEN');
    $gateway = env('JAPATI_GATEWAY_NUMBER');

    $judul = match ($status) {
        'aktif' => '✅ Booking Aktif',
        'masa_tenggang' => '⚠️ Booking Masa Tenggang',
        'expired' => '❌ Booking Expired',
            'unpaid' => '🟡 Belum Bayar', // ← TAMBAH INI
        'paid' => '🟢 Sudah Bayar',
        default => 'ℹ️ Status Booking',
    };

    $isi = match ($status) {
        'aktif' => "Booking kamu untuk kamar $nomorKamar sudah *AKTIF*.",
        'masa_tenggang' => "Booking kamu masuk *MASA TENGGANG*. Segera bayar ulang.",
        'expired' => "Booking kamu untuk kamar $nomorKamar sudah *EXPIRED*. Hubungi admin.",
        'paid' => "Pembayaran untuk kamar $nomorKamar sudah *BERHASIL*. Terima kasih!",
          'unpaid' => "Booking kamu untuk kamar $nomorKamar *BELUM DIBAYAR*. Segera lakukan pembayaran.", // ← TAMBAH INI
        default => 'Status booking tidak diketahui.',
    };

    $message = "*$judul*\n\nNama: $nama\nKamar: $nomorKamar\n\n$isi";

    $res = Http::asForm()->withHeaders([
        'Authorization' => 'Bearer ' . $apiToken,
        'X-Requested-With' => 'XMLHttpRequest',
    ])->post('https://app.japati.id/api/send-message', [
        'gateway' => $gateway,
        'number' => $nomorHP,
        'type' => 'text',
        'message' => $message,
    ]);

    if (!$res->successful()) {
        Log::error('❌ Gagal kirim WA ke ' . $nomorHP . ': ' . $res->body());
    } else {
        Log::info('✅ WA berhasil dikirim ke ' . $nomorHP);
    }
}


}

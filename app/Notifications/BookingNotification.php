<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingNotification extends Notification
{
    protected $status;
    protected $nomorKamar;

    public function __construct($status, $nomorKamar)
    {
        $this->status = $status;
        $this->nomorKamar = $nomorKamar;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $judul = match ($this->status) {
            'aktif' => 'Booking Anda Telah Aktif',
            'masa_tenggang' => 'Booking Anda Memasuki Masa Tenggang',
            'expired' => 'Booking Anda Telah Expired',
            default => 'Status Booking Anda',
        };

        $isi = match ($this->status) {
            'aktif' => 'Booking Anda untuk kamar ' . $this->nomorKamar . ' telah aktif.',
            'masa_tenggang' => 'Booking Anda masuk masa tenggang! Segera lakukan pembayaran ulang.',
            'expired' => 'Booking Anda telah expired! Silakan hubungi admin.',
            default => 'Status booking tidak diketahui.',
        };

        return (new MailMessage)
            ->subject($judul)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line($isi)
            ->action('Lihat Tagihan', route('tagihanpelanggan.index'))
            ->line('Terima kasih telah menggunakan layanan kami.');
    }
}

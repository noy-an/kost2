<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingNotification extends Notification
{
    public function via($notifiable)
    {
        return ['mail']; // Kirim lewat email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking Anda Telah Masa Tenggang')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Booking Anda telah disetujui oleh admin.')
            ->action('Lihat Tagihan', route('tagihanpelanggan.index'))
            ->line('Silakan cek tagihan Anda dan segera lakukan pembayaran.');
    }
}

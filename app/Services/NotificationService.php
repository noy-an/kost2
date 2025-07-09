<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function createNotification(User $user, string $type, string $title, string $message, array $data = []): Notification
    {
        return Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'user_id' => $user->id,
            'data' => $data
        ]);
    }

    public function createBookingApprovedNotification(User $user, $bookingId, $roomName): Notification
    {
        return $this->createNotification(
            $user,
            'booking_approved',
            'Booking Disetujui',
            "Booking Anda untuk kamar {$roomName} telah disetujui. Silakan cek tagihan untuk melakukan pembayaran.",
            ['booking_id' => $bookingId, 'room_name' => $roomName]
        );
    }

    public function createPaymentReminderNotification(User $user, $amount, $dueDate): Notification
    {
        return $this->createNotification(
            $user,
            'payment_reminder',
            'Pengingat Pembayaran',
            "Anda memiliki tagihan sebesar Rp " . number_format($amount, 0, ',', '.') . " yang jatuh tempo pada {$dueDate}. Silakan lakukan pembayaran segera.",
            ['amount' => $amount, 'due_date' => $dueDate]
        );
    }

    public function createPaymentConfirmationNotification(User $user, $amount): Notification
    {
        return $this->createNotification(
            $user,
            'payment_confirmation',
            'Pembayaran Berhasil',
            "Pembayaran Anda sebesar Rp " . number_format($amount, 0, ',', '.') . " telah berhasil diproses.",
            ['amount' => $amount]
        );
    }

    public function getUserNotifications(User $user, int $limit = 10)
    {
        return Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    public function markAsRead(Notification $notification): bool
    {
        if ($notification->isUnread()) {
            $notification->markAsRead();
            return true;
        }
        return false;
    }

    public function markAllAsRead(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
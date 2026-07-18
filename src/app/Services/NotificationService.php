<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Order;

class NotificationService
{
    /**
     * Kirim notifikasi saat status pesanan berubah.
     */
    public function notifyStatusChange(Order $order): void
    {
        $messages = [
            'pending'    => "Pesanan Anda #{$order->id} sedang menunggu konfirmasi admin.",
            'approved'   => "Pesanan Anda #{$order->id} telah dikonfirmasi dan akan segera dikerjakan.",
            'dikerjakan' => "Pesanan Anda #{$order->id} sedang dalam proses pengerjaan.",
            'selesai'    => "Pesanan Anda #{$order->id} telah selesai dikerjakan! Silakan cek dokumentasi hasil.",
            'cancelled'  => "Pesanan Anda #{$order->id} telah dibatalkan.",
        ];

        $message = $messages[$order->status] ?? "Status pesanan #{$order->id} telah diperbarui.";

        Notification::create([
            'user_id'          => $order->user_id,
            'order_id'         => $order->id,
            'type'             => 'order_status',
            'message'          => $message,
            'whatsapp_status'  => 'pending',
        ]);
    }

    /**
     * Notifikasi saat harga custom order sudah ditetapkan.
     */
    public function notifyQuoteReady(Order $order): void
    {
        Notification::create([
            'user_id'         => $order->user_id,
            'order_id'        => $order->id,
            'type'            => 'quote_ready',
            'message'         => "Harga untuk pesanan custom Anda #{$order->id} sudah ditetapkan. Silakan lanjutkan pembayaran.",
            'whatsapp_status' => 'pending',
        ]);
    }

    /**
     * Notifikasi setelah pembayaran dikonfirmasi.
     */
    public function notifyPaymentConfirmed(Order $order): void
    {
        Notification::create([
            'user_id'         => $order->user_id,
            'order_id'        => $order->id,
            'type'            => 'payment_confirmed',
            'message'         => "Pembayaran untuk pesanan #{$order->id} telah dikonfirmasi. Terima kasih!",
            'whatsapp_status' => 'pending',
        ]);
    }

    /**
     * Notifikasi admin saat pembayaran baru diterima.
     */
    public function notifyAdminPaymentReceived(Order $order): void
    {
        // Find admin user(s) - assuming admin role exists
        $admins = \App\Models\User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id'         => $admin->id,
                'order_id'        => $order->id,
                'type'            => 'payment_submitted',
                'message'         => "Pembayaran baru diterima untuk pesanan #{$order->id} dari {$order->user->name}. Silakan verifikasi bukti transfer.",
                'whatsapp_status' => 'pending',
            ]);
        }
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        return (bool) Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Tandai semua notifikasi user sebagai sudah dibaca.
     */
    public function markAllAsRead(int $userId): void
    {
        Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}

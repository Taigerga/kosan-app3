<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\NotificationService;

class SendOrderCreatedNotification
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        
        // Notifikasi untuk penghuni
        $this->notificationService->sendNotification(
            $order->user_id,
            'penghuni',
            'Pemesanan Berhasil',
            "Pemesanan kos Anda berhasil dibuat. Tunggu konfirmasi dari pemilik kos.",
            'success',
            '/orders/' . $order->id
        );

        // Notifikasi untuk pemilik kos
        $this->notificationService->sendNotification(
            $order->kos->pemilik_id,
            'pemilik', 
            'Pemesanan Baru',
            "Ada pemesanan baru untuk kos {$order->kos->nama}. Silakan review dan konfirmasi.",
            'info',
            '/pemilik/orders/' . $order->id
        );
    }
}
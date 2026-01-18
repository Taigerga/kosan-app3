<?php

namespace App\Events;

use App\Models\Pembayaran;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pembayaran;

    public function __construct(Pembayaran $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }
}
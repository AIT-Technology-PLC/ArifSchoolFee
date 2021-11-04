<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransferApprovedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transfer;

    public function __construct($transfer)
    {
        $this->transfer = $transfer;
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdjustmentApproved extends Notification
{
    use Queueable;

    public function __construct($adjustment)
    {
        $this->adjustment = $adjustment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'eraser',
            'message' => 'Inventory adjustment has been approved by ' . ucfirst($this->adjustment->approvedBy->name),
            'endpoint' => '/adjustments/' . $this->adjustment->id,
        ];
    }
}

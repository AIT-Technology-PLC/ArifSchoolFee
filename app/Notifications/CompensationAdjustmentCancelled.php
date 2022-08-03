<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CompensationAdjustmentCancelled extends Notification
{
    use Queueable;

    public function __construct($compensationAdjustment)
    {
        $this->compensationAdjustment = $compensationAdjustment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fa-solid fa-circle-dollar-to-slot',
            'message' => 'Compensation Adjustment has been cancelled by ' . ucfirst($this->compensationAdjustment->cancelledBy->name),
            'endpoint' => '/compensation-adjustments/' . $this->compensationAdjustment->id,
        ];
    }
}
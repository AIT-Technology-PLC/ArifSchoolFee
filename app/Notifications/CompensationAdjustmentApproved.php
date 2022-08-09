<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CompensationAdjustmentApproved extends Notification
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
            'message' => 'Compensation Adjustment has been approved by ' . ucfirst($this->compensationAdjustment->approvedBy->name),
            'endpoint' => '/compensation-adjustments/' . $this->compensationAdjustment->id,
        ];
    }
}
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmployeeTransferApproved extends Notification
{
    use Queueable;

    public function __construct($employeeTransfer)
    {
        $this->employeeTransfer = $employeeTransfer;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-people-arrows-left-right',
            'message' => 'Employee Transfer has been approved by ' . ucfirst($this->employeeTransfer->approvedBy->name),
            'endpoint' => '/employee-transfers/' . $this->employeeTransfer->id,
        ];
    }
}

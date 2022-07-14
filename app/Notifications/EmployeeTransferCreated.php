<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmployeeTransferCreated extends Notification
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
            'message' => 'New Employee Transfer has been created by ' . ucfirst($this->employeeTransfer->createdBy->name),
            'endpoint' => '/employee-transfers/' . $this->employeeTransfer->id,
        ];
    }
}

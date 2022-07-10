<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReturnApproved extends Notification
{
    use Queueable;

    public function __construct($return)
    {
        $this->return = $return;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'arrow-alt-circle-left',
            'message' => 'Return voucher has been approved by '.ucfirst($this->return->approvedBy->name),
            'endpoint' => '/returns/'.$this->return->id,
        ];
    }
}

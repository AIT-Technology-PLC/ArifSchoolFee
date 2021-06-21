<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReturnPrepared extends Notification
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
            'message' => 'Approval request for return voucher prepared by ' . ucfirst($this->return->createdBy->name),
            'endpoint' => '/returns/' . $this->return->id,
        ];
    }
}

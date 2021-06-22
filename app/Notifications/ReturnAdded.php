<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReturnAdded extends Notification
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
            'message' => 'Returned products have been added to inventory',
            'endpoint' => '/returns/' . $this->return->id,
        ];
    }
}

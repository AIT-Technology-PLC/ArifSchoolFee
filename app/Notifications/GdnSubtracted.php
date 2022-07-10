<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GdnSubtracted extends Notification
{
    use Queueable;

    public function __construct($gdn)
    {
        $this->gdn = $gdn;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-invoice',
            'message' => 'Products in delivery order #'.$this->gdn->code.' are subtracted from inventory',
            'endpoint' => '/gdns/'.$this->gdn->id,
        ];
    }
}

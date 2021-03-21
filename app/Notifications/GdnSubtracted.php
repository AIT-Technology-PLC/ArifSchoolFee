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
            'message' => 'DO/GDN has been submitted to customer',
            'endpoint' => '/gdn/' . $this->gdn->id,
        ];
    }
}

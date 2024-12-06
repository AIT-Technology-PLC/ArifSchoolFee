<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SubscriptionPrepared extends Notification
{
    use Queueable;

    protected $school;

    public function __construct($school)
    {
        $this->school = $school;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-school',
            'message' => 'New school subscription created: ' . ucfirst($this->school->name),
            'endpoint' => '/admin/schools/' . $this->school->id, 
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('New School Subscription Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('New school subscription created: ' . ucfirst($this->school->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]); 
    }
}


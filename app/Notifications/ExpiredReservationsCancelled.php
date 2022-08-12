<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ExpiredReservationsCancelled extends Notification
{
    use Queueable;

    private $totalCancelledReservations;

    public function __construct($totalCancelledReservations)
    {
        $this->totalCancelledReservations = $totalCancelledReservations;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->totalCancelledReservations)
            ->append(
                ' ',
                Str::plural('reservation', $this->totalCancelledReservations),
                ' ',
                $this->totalCancelledReservations == 1 ? 'was' : 'were',
                ' expired and ',
                $this->totalCancelledReservations == 1 ? 'has' : 'have',
                ' been cancelled'
            );

        return [
            'icon' => 'archive',
            'message' => $message,
            'endpoint' => '/reservations',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $message = Str::of($this->totalCancelledReservations)
            ->append(
                ' ',
                Str::plural('reservation', $this->totalCancelledReservations),
                ' ',
                $this->totalCancelledReservations == 1 ? 'was' : 'were',
                ' expired and ',
                $this->totalCancelledReservations == 1 ? 'has' : 'have',
                ' been cancelled'
            );

        return (new WebPushMessage)
            ->title('Expired Reservations Cancelled')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($message)
            ->action('View', '/reservations')
            ->vibrate([500, 250, 500, 250]);
    }
}
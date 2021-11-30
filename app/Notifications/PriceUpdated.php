<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class PriceUpdated extends Notification
{
    use Queueable;

    private $price;

    public function __construct($price)
    {
        $this->price = $price;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->price->product->name)
            ->title()
            ->append(' price is updated.');

        return [
            'icon' => 'tags',
            'message' => $message,
            'endpoint' => '/prices',
        ];
    }
}

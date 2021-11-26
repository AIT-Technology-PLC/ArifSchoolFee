<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class PriceUpdated extends Notification
{
    use Queueable;

    private $totalProducts, $prices;

    public function __construct($totalProducts, $prices)
    {
        $this->totalProducts = $totalProducts;

        $this->prices = $prices;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = Str::of(Str::plural('Price', $this->totalProducts))
            ->append(
                ' of ',
                $this->totalProducts,
                ' ',
                Str::plural('product', $this->totalProducts),
                ' ',
                $this->totalProducts > 1 ? 'have been updated.' : 'has been updated.',
            );

        return [
            'icon' => 'tags',
            'message' => $message,
            'endpoint' => '/prices?prices=' . $this->prices->pluck('id')->toArray(),
        ];
    }
}

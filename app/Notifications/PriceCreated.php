<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class PriceCreated extends Notification
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
        $message = Str::of('New ')
            ->append(
                Str::plural('price', $this->totalProducts),
                ' ',
                $this->totalProducts == 1 ? 'was set for ' : 'were set for ',
                $this->totalProducts,
                ' ',
                Str::plural('product', $this->totalProducts),
            );

        return [
            'icon' => 'tags',
            'message' => $message,
            'endpoint' => '/prices?prices=' . $this->prices->pluck('id')->toArray(),
        ];
    }
}

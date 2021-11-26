<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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
        return [
            'icon' => 'file-invoice',
            'message' => 'New price was set for ' . $this->totalProducts,
            'endpoint' => '/prices?prices=' . $this->prices->pluck('id')->toArray(),
        ];
    }
}

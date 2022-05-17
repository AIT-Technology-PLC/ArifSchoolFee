<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class LowProductInventoryLevel extends Notification
{
    use Queueable;

    private $totalLimitedProducts;

    public function __construct($totalLimitedProducts)
    {
        $this->totalLimitedProducts = $totalLimitedProducts;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->totalLimitedProducts)
            ->append(
                ' ',
                Str::plural('product', $this->totalLimitedProducts),
                ' ',
                $this->totalLimitedProducts == 1 ? 'has' : 'have',
                ' reached low level'
            );

        return [
            'icon' => 'balance-scale',
            'message' => $message,
            'endpoint' => '/merchandises/available?level=limited',
        ];
    }
}

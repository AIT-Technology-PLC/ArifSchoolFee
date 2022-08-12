<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ProformaInvoiceExpirationIsClose extends Notification
{
    use Queueable;

    public function __construct($proformaInvoices)
    {
        $this->proformaInvoices = $proformaInvoices;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-invoice-dollar',
            'message' => $this->proformaInvoices->count() . ' ' . Str::plural('proforma invoice', $this->proformaInvoices->count()) . ' ' . ($this->proformaInvoices->count() == 1 ? 'has' : 'have') . ' 5 days or less remaining to be expired',
            'endpoint' => '/proforma-invoices',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Proforma Invoice Expiration Close')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($this->proformaInvoices->count() . ' ' . Str::plural('proforma invoice', $this->proformaInvoices->count()) . ' ' . ($this->proformaInvoices->count() == 1 ? 'has' : 'have') . ' 5 days or less remaining to be expired')
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ProformaInvoicePrepared extends Notification
{
    use Queueable;

    private $proformaInvoice;

    public function __construct($proformaInvoice)
    {
        $this->proformaInvoice = $proformaInvoice;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        $customer = $this->proformaInvoice->customer ? (' for ' . ucfirst($this->proformaInvoice->customer->company_name)) : '';

        return [
            'icon' => 'file-invoice-dollar',

            'message' => 'New Proforma Invoice was prepared by ' .
            ucfirst($this->proformaInvoice->createdBy->name) . $customer,

            'endpoint' => '/proforma-invoices/' . $this->proformaInvoice->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $customer = $this->proformaInvoice->customer ? (' for ' . ucfirst($this->proformaInvoice->customer->company_name)) : '';

        return (new WebPushMessage)
            ->title('Proforma Invoice Prepared')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('New Proforma Invoice was prepared by ' .
                ucfirst($this->proformaInvoice->createdBy->name) . $customer)
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}

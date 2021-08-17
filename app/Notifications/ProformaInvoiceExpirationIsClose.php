<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ProformaInvoiceExpirationIsClose extends Notification
{
    use Queueable;

    public function __construct($proformaInvoices)
    {
        $this->proformaInvoices = $proformaInvoices;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-invoice-dollar',
            'message' => $this->proformaInvoices->count() . ' ' . Str::plural('proforma invoice', $this->proformaInvoices->count()) . ' ' . ($this->proformaInvoices->count() == 1 ? 'has' : 'have') . ' 5 days or less remaining to reach expiry date',
            'endpoint' => '/proforma-invoices',
        ];
    }
}

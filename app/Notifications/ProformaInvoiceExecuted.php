<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProformaInvoiceExecuted extends Notification
{
    use Queueable;

    private $proformaInvoice;

    public function __construct($proformaInvoice)
    {
        $this->proformaInvoice = $proformaInvoice;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-invoice-dollar',
            'message' => 'Proforma Invoice has been executed by ' . ucfirst($this->proformaInvoice->executedBy->name),
            'endpoint' => '/proforma-invoices/' . $this->proformaInvoice->id,
        ];
    }
}

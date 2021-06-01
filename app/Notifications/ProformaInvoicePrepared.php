<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-invoice-dollar',
            'message' => 'Approval request for Proforma Invoice prepared by ' . ucfirst($this->proformaInvoice->createdBy->name),
            'endpoint' => '/proforma-invoices/' . $this->proformaInvoice->id,
        ];
    }
}

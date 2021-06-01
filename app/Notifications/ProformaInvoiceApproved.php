<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProformaInvoiceApproved extends Notification
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
            'message' => 'Proforma Invoice has been approved by ' . ucfirst($this->proformaInvoice->approvedBy->name),
            'endpoint' => '/proforma-invoices/' . $this->proformaInvoice->id,
        ];
    }
}

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
        $customer = $this->proformaInvoice->customer ? (' for '.ucfirst($this->proformaInvoice->customer->company_name)) : '';

        return [
            'icon' => 'file-invoice-dollar',

            'message' => 'New Proforma Invoice was prepared by '.
            ucfirst($this->proformaInvoice->createdBy->name).$customer,

            'endpoint' => '/proforma-invoices/'.$this->proformaInvoice->id,
        ];
    }
}

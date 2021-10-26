<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ExpiredProformaInvoicesCancelled extends Notification
{
    use Queueable;

    private $totalCancelledProfromaInvoices;

    public function __construct($totalCancelledProfromaInvoices)
    {
        $this->totalCancelledProfromaInvoices = $totalCancelledProfromaInvoices;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->totalCancelledProfromaInvoices)
            ->append(
                ' ',
                Str::plural('proforma invoice', $this->totalCancelledProfromaInvoices),
                ' ',
                $this->totalCancelledProfromaInvoices == 1 ? 'was' : 'were',
                ' expired and ',
                $this->totalCancelledProfromaInvoices == 1 ? 'has' : 'have',
                ' been cancelled'
            );

        return [
            'icon' => 'receipt',
            'message' => $message,
            'endpoint' => '/proforma-invoices',
        ];
    }
}

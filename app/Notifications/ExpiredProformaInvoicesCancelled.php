<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

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
        return ['database', WebPushChannel::class];
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

    public function toWebPush($notifiable, $notification)
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

        return (new WebPushMessage)
            ->title('Expired Proforma Invoices Cancelled')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($message)
            ->action('View', '/proforma-invoices')
            ->vibrate([500, 250, 500, 250]);
    }
}
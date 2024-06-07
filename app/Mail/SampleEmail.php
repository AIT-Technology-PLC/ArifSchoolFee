<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SampleEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($name, $amount, $paymentDate, $transactionId, $schoolYear, $schoolName, $Message, $link)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->paymentDate = $paymentDate;
        $this->transactionId = $transactionId;
        $this->schoolYear = $schoolYear;
        $this->schoolName = $schoolName;
        $this->Message = $Message;
        $this->link = $link;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Confirmation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sample',
            with: [
                'name' => $this->name,
                'amount' => $this->amount,
                'paymentDate' => $this->paymentDate,
                'transactionId' => $this->transactionId,
                'schoolYear' => $this->schoolYear,
                'schoolName' => $this->schoolName,
                'Message' => $this->Message,
                'link' => $this->link,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

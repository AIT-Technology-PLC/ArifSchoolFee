<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($data, $subject)
    {
        $this->data = $data;

        $this->subject = $subject;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject:  $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.templete',
            with: [
                'subject' =>  $this->subject,
                'data' =>  $this->data,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

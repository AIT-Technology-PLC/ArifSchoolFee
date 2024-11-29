<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($subject, $receiver, $message_content)
    {
        $this->subject = $subject;
        $this->receiver = $receiver;
        $this->message_content = $message_content;
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
            view: 'emails.email-message-templete',
            with: [
                'subject' =>  $this->subject,
                'receiver' => $this->receiver,
                'message_content' => $this->message_content,
            ],
        );
    }

    public function attachments(): array
    {
            return [];
    }
}

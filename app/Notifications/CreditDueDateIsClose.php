<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CreditDueDateIsClose extends Notification
{
    use Queueable;

    private $totalCredits;

    public function __construct($totalCredits)
    {
        $this->totalCredits = $totalCredits;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->totalCredits)
            ->append(
                Str::plural(' credit', $this->totalCredits),
                ' will be due',
                ' after 5 days or less'
            );

        return [
            'icon' => 'fas fa-money-check',
            'message' => $message,
            'endpoint' => '/credits?type=due',
        ];
    }
}

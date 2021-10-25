<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class BidBondDeadlineIsClose extends Notification
{
    use Queueable;

    private $totalTenders;

    public function __construct($totalTenders)
    {
        $this->totalTenders = $totalTenders;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = Str::of('Bid ')
            ->append(
                Str::plural('bond', $this->totalTenders),
                ' of ',
                $this->totalTenders,
                ' ',
                Str::plural('tender', $this->totalTenders),
                ' will expire',
                ' after 5 days or less'
            );

        return [
            'icon' => 'project-diagram',
            'message' => $message,
            'endpoint' => '/tenders',
        ];
    }
}

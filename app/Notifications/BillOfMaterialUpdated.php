<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class BillOfMaterialUpdated extends Notification
{
    use Queueable;

    private $billOfMaterial;

    public function __construct($billOfMaterial)
    {
        $this->billOfMaterial = $billOfMaterial;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->billOfMaterial->billOfMaterial->name)
            ->title()
            ->append(' Bill of materials is updated.');

        return [
            'icon' => 'tags',
            'message' => $message,
            'endpoint' => '/bill-of-materials',
        ];
    }
}
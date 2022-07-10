<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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
        return [
            'icon' => 'fas fa-clipboard-list',
            'message' => 'Bill Of Material has been updated by '.ucfirst($this->billOfMaterial->createdBy->name),
            'endpoint' => '/bill-of-materials/'.$this->billOfMaterial->id,
        ];
    }
}

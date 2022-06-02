<?php

namespace App\Notifications;

use App\Models\BillOfMaterial;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BillOfMaterialCreated extends Notification
{
    use Queueable;

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
            'message' => 'Bill Of Material has been created by ' . ucfirst($this->billOfMaterial->createdBy->name),
            'endpoint' => '/bill-of-materials/' . $this->billOfMaterial->id,
        ];
    }
}
<?php

namespace App\Http\Livewire;

use Illuminate\Notifications\DatabaseNotification as Notification;
use Livewire\Component;

class Notifications extends Component
{
    public $readNotifications, $unreadNotifications;

    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();

        return $this->redirectToEndpoint($notification);
    }

    public function redirectToEndpoint(Notification $notification)
    {
        return redirect($notification->data['endpoint']);
    }

    public function render()
    {
        $this->readNotifications = auth()->user()->readNotifications;

        $this->unreadNotifications = auth()->user()->unreadNotifications;

        $this->emit("notificationComponentRefreshed");

        return view('livewire.notifications');
    }
}

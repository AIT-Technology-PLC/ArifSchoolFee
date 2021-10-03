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

        return $this->redirectToEndpoint($notification->data['endpoint']);
    }

    public function redirectToEndpoint($endpoint)
    {
        return redirect($endpoint);
    }

    public function render()
    {
        $this->readNotifications = auth()->user()->readNotifications()->take(5)->get();

        $this->unreadNotifications = auth()->user()->unreadNotifications;

        $this->emit("notificationComponentRefreshed");

        return view('livewire.notifications');
    }
}

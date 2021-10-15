<?php

namespace App\Http\Livewire;

use Illuminate\Notifications\DatabaseNotification as Notification;
use Livewire\Component;

class Notifications extends Component
{
    public $readNotifications, $unreadNotifications;

    public function mount()
    {
        $this->readNotifications = auth()->user()->readNotifications()->take(5)->get();

        $this->unreadNotifications = auth()->user()->unreadNotifications;
    }

    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();

        return $this->redirectToEndpoint($notification->data['endpoint']);
    }

    public function redirectToEndpoint($endpoint)
    {
        return redirect($endpoint);
    }

    public function getLatestUnreadNotifications()
    {
        $this->unreadNotifications = auth()->user()->unreadNotifications;

        $this->emit("notificationComponentRefreshed", $this->unreadNotifications->count());
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}

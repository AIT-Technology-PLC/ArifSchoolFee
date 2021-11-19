<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $readNotifications, $unreadNotifications;

    public function mount()
    {
        $this->readNotifications = auth()->user()->readNotifications()->take(5)->get();

        $this->unreadNotifications = auth()->user()->unreadNotifications;
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

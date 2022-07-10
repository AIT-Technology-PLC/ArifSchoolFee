<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $readNotifications;

    public $unreadNotifications;

    public function mount()
    {
        $this->readNotifications = authUser()->readNotifications()->take(5)->get();

        $this->unreadNotifications = authUser()->unreadNotifications;
    }

    public function getLatestUnreadNotifications()
    {
        $this->unreadNotifications = authUser()->unreadNotifications;

        $this->emit('notificationComponentRefreshed', $this->unreadNotifications->count());
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}

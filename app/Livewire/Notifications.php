<?php

namespace App\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $readNotifications;

    public $unreadNotifications;

    public function mount()
    {
        $this->unreadNotifications = authUser()->unreadNotifications()->take(10)->get();

        $this->readNotifications = authUser()->readNotifications()->take(10 - $this->unreadNotifications->count())->get();
    }

    public function getLatestUnreadNotifications()
    {
        $this->unreadNotifications = authUser()->unreadNotifications()->get();

        $this->dispatch('notificationComponentRefreshed', $this->unreadNotifications->count());
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationCounter extends Component
{
    public $totalUnreadNotifications;

    protected $listeners = [
        'notificationComponentRefreshed' => 'getTotalUnreadNotifications',
    ];

    public function getTotalUnreadNotifications()
    {
        $this->totalUnreadNotifications = cache()->store('array')->rememberForever('totalUnreadNotifications', function () {
            return auth()->user()->unreadNotifications()->count();
        });
    }

    public function render()
    {
        $this->getTotalUnreadNotifications();

        return view('livewire.notification-counter');
    }
}

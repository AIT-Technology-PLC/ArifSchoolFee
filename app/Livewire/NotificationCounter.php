<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class NotificationCounter extends Component
{
    public $totalUnreadNotifications;

    public $class;

    protected $listeners = [
        'notificationComponentRefreshed' => 'getTotalUnreadNotifications',
    ];

    public function mount()
    {
        $this->totalUnreadNotifications = Cache::store('array')
            ->rememberForever(authUser()->id.'_'.'totalUnreadNotifications', function () {
                return authUser()->unreadNotifications()->count();
            });
    }

    public function getTotalUnreadNotifications($value)
    {
        $this->totalUnreadNotifications = $value;
    }

    public function render()
    {
        return view('livewire.notification-counter');
    }
}

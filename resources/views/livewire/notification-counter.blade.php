<a
    x-data
    @click="$dispatch('display-notifications')"
    class="{{ $class }}"
>
    <span class="icon">
        <i class="fas fa-bell"></i>
    </span>
    <span class="notification-counter has-text-white has-text-centered has-text-weight-bold bg-green {{ $totalUnreadNotifications ? '' : '' }}">
        {{ $totalUnreadNotifications > 9 ? '9+' : $totalUnreadNotifications }}
    </span>
</a>

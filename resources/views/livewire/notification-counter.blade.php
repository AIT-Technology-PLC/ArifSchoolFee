<a class="{{ $class }}">
    <span class="icon">
        <i class="fas fa-bell"></i>
    </span>
    <span class="notification-counter has-text-white has-text-centered has-text-weight-bold bg-brown {{ $totalUnreadNotifications ? '' : 'is-hidden' }}">
        {{ $totalUnreadNotifications }}
    </span>
</a>

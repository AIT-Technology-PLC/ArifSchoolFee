<div id="notificationBox" class="notification-box mt-1 is-hidden">
    <article class="message m-lr-10">
        <div class="message-header bg-green has-text-white">
            <p class="is-size-7">Notifications</p>
            <button id="closeNotificationButton" class="delete"></button>
        </div>
        <div id="notificationBody" class="message-body is-overflow has-background-white p-0" style="max-height: 300px !important">
            @forelse ($unreadNotifications as $unreadNotification)
                <div class="columns is-marginless has-background-white-bis text-green py-3 is-size-6-5 is-mobile">
                    <div class="column is-1">
                        <span class="icon is-small">
                            <i class="fas fa-{{ $unreadNotification->data['icon'] }}"></i>
                        </span>
                    </div>
                    <div class="column is-11 pl-1">
                        <a data-notification-id="{{ $unreadNotification->id }}" class="unreadNotifications is-not-underlined" href="{{ $unreadNotification->data['endpoint'] }}">
                            {{ $unreadNotification->data['message'] }}
                        </a>
                        <br>
                        <span class="is-size-7 has-text-weight-bold">
                            {{ $unreadNotification->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                <hr class="mt-0 mb-0">
            @empty
                <div class="columns is-marginless has-background-white has-text-black py-3 is-size-6-5 is-mobile">
                    <div class="column is-12">
                        <span>
                            No new notifications
                        </span>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="box radius-top-0 has-background-white-ter has-text-centered p-3">
            <a href="{{ route('notifications.index') }}" class="is-size-7 text-green has-text-weight-bold is-not-underlined">
                See all notifications
            </a>
        </div>
    </article>
</div>

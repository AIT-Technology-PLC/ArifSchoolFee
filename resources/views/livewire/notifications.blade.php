<div wire:poll.keep-alive.300000ms>
    <section id="notificationBox" class="notification-box mt-1 is-hidden">
        <article class="message m-lr-10">
            <div class="message-header bg-green has-text-white">
                <p class="is-size-7">Notifications</p>
                <button id="closeNotificationButton" class="delete"></button>
            </div>
            <div class="message-body is-overflow has-background-white p-0" style="max-height: 300px !important">
                @foreach ($unreadNotifications as $unreadNotification)
                    <div wire:click="markAsRead('{{ $unreadNotification->id }}')" class="columns is-marginless has-background-white-ter text-green py-1 is-size-6-5 is-mobile is-clickable">
                        <div class="column is-1 pb-0">
                            <span class="icon is-small">
                                <i class="fas fa-{{ $unreadNotification->data['icon'] }}"></i>
                            </span>
                        </div>
                        <div class="column is-11 pl-1 pb-0">
                            <span>
                                {{ $unreadNotification->data['message'] }}
                            </span>
                            <br>
                            <span class="is-size-7 has-text-weight-light">
                                {{ $unreadNotification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @endforeach
                @foreach ($readNotifications as $readNotification)
                    @if ($loop->index == 5)
                        @break
                    @endif
                    <div wire:click="redirectToEndpoint('{{ $readNotification->data['endpoint'] }}')" class="columns is-marginless has-background-white text-green py-1 is-size-6-5 is-mobile is-clickable">
                        <div class="column is-1 pb-0">
                            <span class="icon is-small">
                                <i class="fas fa-{{ $readNotification->data['icon'] }}"></i>
                            </span>
                        </div>
                        <div class="column is-11 pl-1 pb-0">
                            <span>
                                {{ $readNotification->data['message'] }}
                            </span>
                            <br>
                            <span class="is-size-7 has-text-weight-light">
                                {{ $readNotification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @endforeach
                @if ($unreadNotifications->isEmpty() && $readNotifications->isEmpty())
                    <div class="columns is-marginless has-background-white has-text-black py-3 is-size-6-5 is-mobile">
                        <div class="column is-12">
                            <span>
                                No notifications
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="box radius-top-0 has-background-white-bis has-text-centered p-3">
                <a href="{{ route('notifications.index') }}" class="is-size-7 text-green has-text-weight-bold is-not-underlined">
                    See all notifications
                </a>
            </div>
        </article>
    </section>
</div>

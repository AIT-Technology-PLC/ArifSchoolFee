<td class="actions">
    <a href="{{ route('notifications.show', $notification->id) }}">
        <span class="tag btn-purple is-outlined is-small text-green has-text-weight-medium">
            <span class="icon">
                <i class="fas fa-info-circle"></i>
            </span>
            <span>
                View
            </span>
        </span>
    </a>
    @if (!$notification->read())
        <form
            x-data="swal('mark as read', 'mark this notification as read')"
            class="is-inline"
            action="{{ route('notifications.update', $notification->id) }}"
            method="post"
            @submit.prevent="open"
        >
            @csrf
            @method('PATCH')
            <button
                class="tag btn-green is-outlined is-small text-green has-text-weight-medium is-pointer"
                x-ref="submitButton"
            >
                <span class="icon">
                    <i class="fas fa-check-double"></i>
                </span>
                <span>
                    Mark as read
                </span>
            </button>
        </form>
    @endif
    <x-common.delete-button
        route="notifications.destroy"
        :id="$notification->id"
    />
</td>

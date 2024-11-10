<td class="actions">
    <x-common.action-buttons
        :buttons="['delete', 'details']"
        model="notifications"
        :id="$notification->id"
    />

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
            <x-common.button
                tag="button"
                mode="button"
                data-title="Mark as read"
                icon="fas fa-check-double"
                class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
                x-ref="submitButton"
            />
        </form>
    @endif
</td>

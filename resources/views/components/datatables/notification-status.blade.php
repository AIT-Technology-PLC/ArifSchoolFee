<td>
    @if ($notification->read())
        <span class="tag is-small bg-green has-text-white">
            Seen
        </span>
    @else
        <span class="tag is-small has-background-grey-dark has-text-white">
            Unseen
        </span>
    @endif
</td>

@if ($activityLog->event == 'created')
    <span class="tag is-small bg-gold has-text-white">
        <span>
            Create
        </span>
    </span>
@elseif ($activityLog->event == 'deleted')
    <span class="tag is-small bg-purple has-text-white">
        <span>
            Delete
        </span>
    </span>
@elseif ($activityLog->event == 'updated')
    <span class="tag is-small bg-green has-text-white">
        <span>
            Update
        </span>
    </span>
@endif

@if ($warning->type == 'Initial Warning')
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-circle-exclamation"></i>
        </span>
        <span>
            Initial Warning
        </span>
    </span>
@elseif ($warning->type == 'Affirmation Warning')
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-circle-exclamation"></i>
        </span>
        <span>
            Affirmation Warning
        </span>
    </span>
@else
    <span class="tag is-small bg-brown has-text-white">
        <span class="icon">
            <i class="fas fa-circle-exclamation"></i>
        </span>
        <span>
            Final Warning
        </span>
    </span>
@endif

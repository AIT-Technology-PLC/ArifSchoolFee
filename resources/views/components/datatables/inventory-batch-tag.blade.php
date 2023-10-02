@if (!isset($content))
    <span class="tag is-small bg-green has-text-white">
        N/A
    </span>
@elseif ($content < now())
    <span class="tag is-small bg-purple has-text-white">
        {{ $content->toFormattedDateString() }}
    </span>
@elseif($content > now())
    <span class="tag is-small bg-green has-text-white">
        {{ $content->toFormattedDateString() }}
    </span>
@endif

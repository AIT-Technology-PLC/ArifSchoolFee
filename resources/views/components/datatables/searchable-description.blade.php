{!! is_null($description) ? 'N/A' : substr(strip_tags($description), 0, 20) . '...' !!}
<span class="is-hidden">
    {!! $description ?? '' !!}
</span>

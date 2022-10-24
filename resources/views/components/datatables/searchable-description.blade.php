<div>
    {!! is_null($description) ? 'N/A' : substr($description, 0, 20) . '...' !!}
</div>
<div class="is-hidden">
    {!! $description ?? '' !!}
</div>

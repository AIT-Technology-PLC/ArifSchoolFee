<div>
    {!! is_null($description)
        ? 'N/A'
        : str($description)->stripTags()->limit(20) !!}
</div>
<div class="is-hidden">
    {!! $description ?? '' !!}
</div>

@props(['amount', 'borderColor', 'textColor', 'label'])

<div
    class="box {{ $textColor }} has-text-centered"
    style="border-left: 2px solid {{ $borderColor }};"
>
    <div class="is-size-3 has-text-weight-bold">
        {{ $amount }}
    </div>
    <div class="is-uppercase is-size-7">
        {{ $label }}
    </div>
</div>

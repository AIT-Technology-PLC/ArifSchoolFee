@props(['amount', 'borderColor', 'textColor', 'label'])

<div class="column is-4 p-lr-0">
    <div class="box {{ $textColor }} has-text-centered" style="border-left: 2px solid {{ $borderColor }};">
        <div class="is-size-3 has-text-weight-bold">
            {{ $amount }}
        </div>
        <div class="is-uppercase is-size-7">
            {{ $label }}
        </div>
    </div>
</div>

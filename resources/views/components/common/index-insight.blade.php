@props(['amount', 'borderColor', 'textColor', 'label', 'labelTextSize' => 'is-size-3'])

<div
    class="box {{ $textColor }} has-text-centered"
    style="border-left: 2px solid {{ $borderColor }};"
>
    <div class="{{ $labelTextSize }} has-text-weight-bold">
        {{ $amount }}
    </div>
    <div class="is-uppercase is-size-7">
        {{ $label }}
    </div>
</div>

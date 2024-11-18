@props(['model', 'headValue', 'boxColor', 'amount', 'icon'])

<div class="box  {{ $boxColor ?? 'bg-softblue' }} has-text-white is-borderless">
    <div class="columns is-marginless is-vcentered is-mobile">
        <div class="column is-4-tablet is-6-mobile has-text-centered py-0 p-lr-0">
            <div class="is-size-7 is-uppercase mb-4">
                {{ $headValue ?? null }}
            </div>
            <x-common.icon
                name="{{ $icon }}"
                class="is-large is-size-2 is-size-1-mobile"
            />
        </div>
        <div class="column ml-3 m-lr-0 py-0 p-lr-0">
            <div class="is-size-3 has-text-weight-bold">
                {{ $amount }}
            </div>
            <div class="is-size-7 is-uppercase">
                {{ $model }}
            </div>
        </div>
    </div>
</div>

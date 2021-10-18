@props(['model', 'amount', 'icon'])

<div class="box text-green">
    <div class="columns is-marginless is-vcentered is-mobile">
        <div class="column has-text-centered is-paddingless">
            <x-common.icon name="{{ $icon }}" class="is-large is-size-1" />
        </div>
        <div class="column is-paddingless">
            <div class="is-size-3 has-text-weight-bold">
                {{ $amount }}
            </div>
            <div class="is-size-7 is-uppercase">
                Total {{ $model }}
            </div>
        </div>
    </div>
</div>

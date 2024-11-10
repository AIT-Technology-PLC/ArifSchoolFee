@props(['filters'])

<div
    {{ $attributes->merge(['class' => 'box is-shadowless bg-lightblue py-0']) }}
    x-data="laravelDatatableFilter({{ $filters }})"
>
    <div class="level mt-0 m-btm-0">
        <div class="level-left">
            <div class="level-item">
                <div>
                    <h6 class="text-blue has-text-centered has-text-weight-bold">
                        <span class="icon">
                            <i class="fas fa-filter"></i>
                        </span>
                        <span> Filters </span>
                    </h6>
                </div>
            </div>
        </div>
        <div class="level-right">
            <div class="level-item">
                <div class="is-hidden-mobile">
                    <x-common.button
                        tag="button"
                        mode="button"
                        label="Clear Filters"
                        class="is-small btn-softblue is-outlined is-rounded"
                        x-on:click="clear"
                    />
                    <x-common.button
                        tag="button"
                        mode="button"
                        label="Apply"
                        class="is-small bg-blue has-text-white is-rounded"
                        x-on:click="filter"
                    />
                </div>
            </div>
        </div>
    </div>
    {{ $slot }}
    <div class="buttons is-centered my-4 is-hidden-tablet">
        <x-common.button
            tag="button"
            mode="button"
            label="Clear Filters"
            class="is-small btn-softblue is-outlined is-rounded"
            x-on:click="clear"
        />
        <x-common.button
            tag="button"
            mode="button"
            label="Apply"
            class="is-small bg-softblue has-text-white is-rounded"
            x-on:click="filter"
        />
    </div>
</div>

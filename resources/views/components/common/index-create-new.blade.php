@props(['route', 'model'])

<div class="box text-purple">
    <div class="columns is-marginless is-vcentered is-mobile">
        <div class="column is-paddingless has-text-centered">
            <div class="is-uppercase is-size-7">
                {{ $slot ?? '' }}
            </div>
            <div class="is-size-3">
                <a href="{{ route($route) }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span class="is-capitalized"> Create {{ $model }} </span>
                </a>
            </div>
        </div>
    </div>
</div>

@props(['message'])

@if ($message)
    <div {{ $attributes->merge(['class' => 'box is-shadowless bg-lightgreen has-text-left mb-5']) }}>
        <p class="has-text-grey text-green is-size-6">
            <x-common.icon name="fas fa-check-circle" />
            <span> {{ $message }} </span>
        </p>
    </div>
@endif

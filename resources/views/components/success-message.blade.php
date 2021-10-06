@props(['message'])

@if ($message)
    <div {{ $attributes->merge(['class' => 'box is-shadowless bg-lightgreen has-text-left mb-5']) }}>
        <p class="has-text-grey text-green is-size-6">
            <span class="icon">
                <i class="fas fa-check-circle"></i>
            </span>
            <span>
                {{ $message }}
            </span>
        </p>
    </div>
@endif

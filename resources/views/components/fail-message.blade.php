@props(['message', 'marginBottom' => '5'])

@if ($message)
    <div class="box is-shadowless bg-lightpurple has-text-left mb-{{ $marginBottom }}">
        <p class="has-text-grey text-purple is-size-6">
            <span class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </span>
            <span>
                {{ $message }}
            </span>
        </p>
    </div>
@endif

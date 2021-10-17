@props(['message', 'marginBottom' => '5'])

@if (isset($message) && is_string($message))
    <div class="box is-shadowless bg-lightpurple text-purple mb-{{ $marginBottom }}">
        <p>
            <span class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </span>
            <span>
                {{ $message }}
            </span>
        </p>
        {{ $slot ?? '' }}
    </div>
@elseif (isset($message) && is_countable($message))
    <div class="box is-shadowless bg-lightpurple text-purple mb-{{ $marginBottom }}">
        @foreach ($message as $item)
            <span class="icon">
                <i class="fas fa-times-circle"></i>
            </span>
            <span>
                {{ $item }}
            </span>
            <br>
        @endforeach
    </div>
@endif

@props(['message', 'marginBottom' => '5'])

@if (isset($message) && is_string($message))
    <div class="box is-shadowless bg-lightpurple text-purple mb-{{ $marginBottom }}">
        <p>
            <x-common.icon name="fas fa-exclamation-circle" />
            <span> {{ $message }} </span>
        </p>
        {{ $slot ?? '' }}
    </div>
@elseif (isset($message) && is_countable($message))
    <div class="box is-shadowless bg-lightpurple text-purple mb-{{ $marginBottom }}">
        @foreach ($message as $item)
            <x-common.icon name="fas fa-times-circle" />
            <span> {{ $item }} </span>
            <br>
        @endforeach
    </div>
@endif

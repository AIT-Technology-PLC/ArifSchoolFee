<textarea
    {{ $attributes->merge(['class' => 'textarea']) }}
    cols="30"
    rows="3"
>{{ $slot }}</textarea>

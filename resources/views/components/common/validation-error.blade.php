@props(['property'])

@error($property)
    <span class="help has-text-danger">
        {{ $message }}
    </span>
@enderror

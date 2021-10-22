@props(['name', 'value', 'checked', 'radioClass'])

<label {{ $attributes->merge(['class' => 'radio has-text-grey']) }}>
    <input
        type="radio"
        name="{{ $name }}"
        value="{{ $value }}"
        class="{{ $radioClass }}"
        {{ $checked ? 'checked' : '' }}
    >
    {{ $slot ?? '' }}
</label>

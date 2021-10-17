@props(['name', 'value', 'checked'])

<label {{ $attributes->merge(['class' => 'checkbox mr-3 has-text-grey has-text-weight-light']) }}>
    <input name="{{ $name }}" value="{{ $value }}" type="checkbox" {{ $checked ? 'checked' : '' }}>
    {{ $slot ?? '' }}
</label>

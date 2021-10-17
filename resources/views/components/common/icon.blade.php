@props(['name'])

<span {{ $attributes->merge(['class' => 'icon']) }}>
    <i class="{{ $name }}"></i>
</span>

@props(['id', 'name'])

<div {{ $attributes->merge(['class' => 'select']) }}>
    <select id="{{ $id }}" name="{{ $name }}">
        {{ $slot }}
    </select>
</div>

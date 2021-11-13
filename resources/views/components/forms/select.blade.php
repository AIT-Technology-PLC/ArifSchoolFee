@props(['id', 'name'])

<div {{ $attributes->class(['select'])->whereStartsWith('class') }}>
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->whereDoesntStartWith('class') }}
    >
        {{ $slot }}
    </select>
</div>

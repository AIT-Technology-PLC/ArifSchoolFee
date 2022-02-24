@props(['id' => null, 'name' => null])

<div {{ $attributes->class(['select'])->whereStartsWith('class') }}>
    <select
        {{ $id ? str('id=')->append($id) : '' }}
        {{ $name ? str('name=')->append($name) : '' }}
        {{ $attributes->whereDoesntStartWith('class') }}
    >
        {{ $slot }}
    </select>
</div>

@props(['id' => null, 'name' => null])

<div {{ $attributes->class(['select'])->whereStartsWith('class') }}>
    <select
        {{ $id ? Str::of('id=')->append($id) : '' }}
        {{ $name ? Str::of('name=')->append($name) : '' }}
        {{ $attributes->whereDoesntStartWith('class') }}
    >
        {{ $slot }}
    </select>
</div>

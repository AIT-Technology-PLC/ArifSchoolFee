<select
    id="{{ $id }}"
    name="{{ $name }}"
    {{ $attributes->whereStartsWith('x-init') }}
    {{ $attributes->class([]) }}
>
    @foreach ($warehouses as $warehouse)
        <option></option>

        <option
            value="{{ $warehouse->$value }}"
            {{ $selectedId == $warehouse->$value ? 'selected' : '' }}
        >
            {{ $warehouse->name }}
        </option>
    @endforeach
</select>

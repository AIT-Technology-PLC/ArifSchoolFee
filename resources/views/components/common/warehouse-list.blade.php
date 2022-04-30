<select
    id="{{ $id }}"
    name="{{ $name }}"
    x-init="{{ $attributes->get('x-init') ?? 'initSelect2($el, "Branch")' }}"
    class="{{ $attributes->get('class') }}"
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

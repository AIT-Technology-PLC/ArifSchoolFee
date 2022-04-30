<select
    id="{{ $id }}"
    name="{{ $name }}"
    x-init="{{ $attributes->get('x-init') ?? 'initSelect2($el, "Supplier")' }}"
    class="{{ $attributes->get('class') }}"
>
    @foreach ($suppliers as $supplier)
        <option></option>

        <option
            value="{{ $supplier->$value }}"
            {{ $selectedId == $supplier->$value ? 'selected' : '' }}
        >
            {{ $supplier->company_name }}
        </option>
    @endforeach
    <option value=" ">None</option>
</select>

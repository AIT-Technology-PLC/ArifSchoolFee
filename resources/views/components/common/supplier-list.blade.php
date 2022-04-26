<select
    id="{{ $id }}"
    name="{{ $name }}"
    {{ $attributes->whereStartsWith('x-init') }}
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

<select
    id="{{ $id }}"
    name="{{ $name }}"
    {{ $attributes->whereStartsWith('x-init') }}
>
    @foreach ($customers as $customer)
        <option></option>

        <option
            value="{{ $customer->$value }}"
            {{ $selectedId == $customer->$value ? 'selected' : '' }}
        >
            {{ $customer->company_name }}
        </option>
    @endforeach
    <option value=" ">None</option>
</select>

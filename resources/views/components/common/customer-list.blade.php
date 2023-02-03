<select
    id="{{ $id }}"
    name="{{ $name }}"
    x-init="{{ $attributes->get('x-init') ?? 'initSelect2($el, "Customer")' }}"
    class="{{ $attributes->get('class') }}"
    {{ $attributes->whereDoesntStartWith('class') }}
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

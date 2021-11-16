<x-forms.select
    id="{{ $id }}"
    name="{{ $name }}"
    x-init="$('#{{ $id }}').select2({placeholder: 'Select Customer', allowClear: true})"
>
    @foreach ($customers as $customer)
        <option></option>

        <option
            value="{{ $customer->$value }}"
            {{ $selectedCustomerId == $customer->$value ? 'selected' : '' }}
        >
            {{ $customer->company_name }}
        </option>
    @endforeach
    <option value=" ">None</option>
</x-forms.select>

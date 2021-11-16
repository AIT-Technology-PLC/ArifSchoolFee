<select
    id="customer_id"
    name="customer_id"
    x-init="$('#customer_id').select2({placeholder: 'Select Customer', allowClear: true})"
>
    @foreach ($customers as $customer)
        <option></option>
        <option
            value="{{ $customer->id }}"
            {{ $selectedCustomerId == $customer->id ? 'selected' : '' }}
        >{{ $customer->company_name }}</option>
    @endforeach
    <option value=" ">None</option>
</select>

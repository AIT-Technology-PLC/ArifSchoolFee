@props(['type' => ''])

<option disabled>Select Product Type</option>
<option
    value="Finished Goods"
    {{ $type == 'Finished Goods' ? 'selected' : '' }}
>Finished Goods</option>
@if (userCompany()->plan->isPremium())
    <option
        value="Raw Material"
        {{ $type == 'Raw Material' ? 'selected' : '' }}
    >Raw Material</option>
@endif
<option
    value="Services"
    {{ $type == 'Services' ? 'selected' : '' }}
>Services</option>

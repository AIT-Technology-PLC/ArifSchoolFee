@props(['type' => ''])

<option disabled>Select Product Type</option>
<option
    value="Finished Goods"
    {{ $type == 'Finished Goods' ? 'selected' : '' }}
>Finished Goods</option>
<option
    value="Raw Material"
    {{ $type == 'Raw Material' ? 'selected' : '' }}
>Raw Material</option>
<option
    value="Services"
    {{ $type == 'Services' ? 'selected' : '' }}
>Services</option>

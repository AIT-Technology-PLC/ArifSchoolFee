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
    value="Non-inventory Product"
    {{ $type == 'Non-inventory Product' ? 'selected' : '' }}
>Non-inventory Product</option>
<option
    value="Services"
    {{ $type == 'Services' ? 'selected' : '' }}
>Services</option>

@props(['type' => ''])

<option disabled>Select Product Type</option>
<option
    value="Merchandise Inventory"
    {{ $type == 'Merchandise Inventory' ? 'selected' : '' }}
>Merchandise Inventory</option>

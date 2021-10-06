@props(['selectedUnitType' => ''])

<option selected disabled>Select Measurement Unit</option>
<option value="Box" {{ $selectedUnitType == 'Box' ? 'selected' : '' }}>Box</option>
<option value="Centimeter" {{ $selectedUnitType == 'Centimeter' ? 'selected' : '' }}>Centimeter</option>
<option value="Kilogram" {{ $selectedUnitType == 'Kilogram' ? 'selected' : '' }}>Kilogram</option>
<option value="Liter" {{ $selectedUnitType == 'Liter' ? 'selected' : '' }}>Liter</option>
<option value="Meter" {{ $selectedUnitType == 'Meter' ? 'selected' : '' }}>Meter</option>
<option value="Metric Ton" {{ $selectedUnitType == 'Metric Ton' ? 'selected' : '' }}>Metric Ton</option>
<option value="Packet" {{ $selectedUnitType == 'Packet' ? 'selected' : '' }}>Packet</option>
<option value="Piece" {{ $selectedUnitType == 'Piece' ? 'selected' : '' }}>Piece</option>
<option value="Quintal" {{ $selectedUnitType == 'Quintal' ? 'selected' : '' }}>Quintal</option>
<option value="Square Meter" {{ $selectedUnitType == 'Square Meter' ? 'selected' : '' }}>Square Meter</option>

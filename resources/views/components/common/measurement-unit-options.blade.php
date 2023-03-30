@props(['selectedUnitType' => ''])

<option
    selected
    disabled
>Select Measurement Unit</option>
<option
    value="Ampule"
    {{ $selectedUnitType == 'Ampule' ? 'selected' : '' }}
>Ampule</option>
<option
    value="Bag"
    {{ $selectedUnitType == 'Bag' ? 'selected' : '' }}
>Bag</option>
<option
    value="Barrel"
    {{ $selectedUnitType == 'Barrel' ? 'selected' : '' }}
>Barrel</option>
<option
    value="Bot"
    {{ $selectedUnitType == 'Bot' ? 'selected' : '' }}
>Bot</option>
<option
    value="Box"
    {{ $selectedUnitType == 'Box' ? 'selected' : '' }}
>Box</option>
<option
    value="Carton"
    {{ $selectedUnitType == 'Carton' ? 'selected' : '' }}
>Carton</option>
<option
    value="Centimeter"
    {{ $selectedUnitType == 'Centimeter' ? 'selected' : '' }}
>Centimeter</option>
<option
    value="Day"
    {{ $selectedUnitType == 'Day' ? 'selected' : '' }}
>Days</option>
<option
    value="Galon"
    {{ $selectedUnitType == 'Galon' ? 'selected' : '' }}
>Galon</option>
<option
    value="Hour"
    {{ $selectedUnitType == 'Hour' ? 'selected' : '' }}
>Hours</option>
<option
    value="Kilogram"
    {{ $selectedUnitType == 'Kilogram' ? 'selected' : '' }}
>Kilogram</option>
<option
    value="Liter"
    {{ $selectedUnitType == 'Liter' ? 'selected' : '' }}
>Liter</option>
<option
    value="Lump Sum"
    {{ $selectedUnitType == 'Lump Sum' ? 'selected' : '' }}
>Lump Sum (LS)</option>
<option
    value="Meter"
    {{ $selectedUnitType == 'Meter' ? 'selected' : '' }}
>Meter</option>
<option
    value="Metric Ton"
    {{ $selectedUnitType == 'Metric Ton' ? 'selected' : '' }}
>Metric Ton</option>
<option
    value="Packet"
    {{ $selectedUnitType == 'Packet' ? 'selected' : '' }}
>Packet</option>
<option
    value="Pairs"
    {{ $selectedUnitType == 'Pairs' ? 'selected' : '' }}
>Pairs</option>
<option
    value="Piece"
    {{ $selectedUnitType == 'Piece' ? 'selected' : '' }}
>Piece</option>
<option
    value="PK"
    {{ $selectedUnitType == 'PK' ? 'selected' : '' }}
>PK</option>
<option
    value="Punch"
    {{ $selectedUnitType == 'Punch' ? 'selected' : '' }}
>Punch</option>
<option
    value="Quintal"
    {{ $selectedUnitType == 'Quintal' ? 'selected' : '' }}
>Quintal</option>
<option
    value="Rack"
    {{ $selectedUnitType == 'Rack' ? 'selected' : '' }}
>Rack</option>
<option
    value="Roll"
    {{ $selectedUnitType == 'Roll' ? 'selected' : '' }}
>Roll</option>
<option
    value="Square Meter"
    {{ $selectedUnitType == 'Square Meter' ? 'selected' : '' }}
>Square Meter</option>
<option
    value="Tube"
    {{ $selectedUnitType == 'Tube' ? 'selected' : '' }}
>Tube</option>
<option
    value="Vial"
    {{ $selectedUnitType == 'Vial' ? 'selected' : '' }}
>Vial</option>
<option
    value="Week"
    {{ $selectedUnitType == 'Week' ? 'selected' : '' }}
>Week</option>

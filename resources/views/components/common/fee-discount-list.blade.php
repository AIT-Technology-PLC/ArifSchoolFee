<x-forms.select
    class="is-fullwidth"
    id="{{ $id }}"
    name="{{ $name }}"
>
    <option 
        selected
        disabled
    >Select Discount Group</option>
    
    @foreach ($feeDiscounts as $feeDiscount)
        <option
            value="{{ $feeDiscount->$value }}"
            {{ $feeDiscount->$value ? 'selected' : '' }}
        >
            {{ $feeDiscount->name }} ({{ $feeDiscount->discount_code }})
        </option>
    @endforeach
</x-forms.select>

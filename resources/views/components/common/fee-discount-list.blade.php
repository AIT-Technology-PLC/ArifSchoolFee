<x-forms.select
    class="is-fullwidth"
    id="{{ $id }}"
    name="{{ $name }}"
    x-model="fee_discount_id"
    @change="const selectedDiscount = $el.options[$el.selectedIndex]; 
    discount_amount = selectedDiscount ? selectedDiscount.dataset.amount : ''"
>
    <option 
        selected
        disabled
        value=""
    >Select Discount Group</option>
    
    @foreach ($feeDiscounts as $feeDiscount)
        <option
            value="{{ $feeDiscount->$value }}"
            data-amount="{{ $feeDiscount->amount }}" 
        >
            {{ $feeDiscount->name }} ({{ money($feeDiscount->amount) }})
        </option>
    @endforeach
</x-forms.select>

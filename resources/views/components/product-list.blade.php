<div class="select is-fullwidth">
    <select id="{{ $name . '[product_id]' }}" name="{{ $name . '[product_id]' }}" class="select2-products" onchange="getProductSelected(this.id, this.value)">
        <option data-code="" data-category=""></option>
        @foreach ($products as $product)
            <option value="{{ $product->id }}" {{ $selectedProductId == $product->id ? 'selected' : '' }} data-code="{{ $product->code ?? '' }}" data-category="{{ $product->productCategory->name }}">{{ $product->name }}</option>
        @endforeach
    </select>
</div>

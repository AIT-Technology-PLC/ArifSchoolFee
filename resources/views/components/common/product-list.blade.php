<div class="select is-fullwidth">
    <select id="{{ $name . '[product_id]' }}" name="{{ $name . '[product_id]' }}" class="select2-products" data-tags="{{ $tags }}" onchange="getProductSelected(this.id, this.value)">
        <option data-code="" data-category=""></option>
        @foreach ($products as $product)
            <option value="{{ $product->id }}" {{ $selectedProductId == $product->id ? 'selected' : '' }} data-code="{{ $product->code ?? '' }}" data-category="{{ Str::of($product->productCategory->name)->replace('  ', ' ') }}">{{ $product->name }}</option>
        @endforeach
        @if (!is_numeric($selectedProductId))
            <option value="{{ $selectedProductId }}" selected data-code="{{ $selectedProductId }}" data-category="{{ $selectedProductId }}">{{ $selectedProductId }}</option>
        @endif
    </select>
</div>

<select id="original-select" class="is-hidden" onchange="getProductSelected(this.id, this.value)">
    <option data-code="" data-category=""></option>
    @foreach ($products as $product)
        <option value="{{ $product->id }}" data-code="{{ $product->code ?? '' }}" data-category="{{ Str::of($product->productCategory->name)->replace('  ', ' ') }}">{{ $product->name }}</option>
    @endforeach
</select>

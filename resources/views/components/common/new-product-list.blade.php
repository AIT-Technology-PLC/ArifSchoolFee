<div
    class="select is-fullwidth"
    {{ $attributes->wire('ignore') }}
>
    <select
        {{ $attributes->merge(['class' => '']) }}
        {{ $attributes->merge(['x-init' => 'initializeSelect2($el)']) }}
        {{ $attributes->whereStartsWith('x-') }}
        style="width: 100% !important"
    >
        <option
            data-code=""
            data-product-category-name=""
            data-product-description=""
        ></option>
        @foreach ($products as $product)
            <option
                value="{{ $product->id }}"
                data-code="{{ $product->code ?? '' }}"
                data-product-category-name="{{ $product->productCategory->name }}"
                data-product-description="{{ $product->description ?? '' }}"
            >
                {{ $product->name }}

                @if ($product->code)
                    ({{ $product->code }})
                @endif
            </option>
        @endforeach
    </select>
</div>

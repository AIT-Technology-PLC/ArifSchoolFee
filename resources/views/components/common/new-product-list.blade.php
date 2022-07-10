<div
    class="select is-fullwidth"
    {{ $attributes->wire('ignore') }}
>
    <select
        {{ $attributes->merge(['class' => '']) }}
        {{ $attributes->merge(['x-init' => 'initializeSelect2($el)']) }}
        {{ $attributes->whereStartsWith('x-') }}
    >
        <option
            data-code=""
            data-product-category-name=""
        ></option>
        @foreach ($products as $product)
            <option
                value="{{ $product->id }}"
                data-code="{{ $product->code ?? '' }}"
                data-product-category-name="{{ str($product->productCategory->name)->replace('  ', ' ') }}"
            >
                {{ $product->name }}

                @if ($product->code)
                    ({{ $product->code }})
                @endif
            </option>
        @endforeach
    </select>
</div>

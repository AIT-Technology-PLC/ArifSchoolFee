<div class="select is-fullwidth">
    <select
        {{ $name ? Str::of('id=')->append($name, '[product_id]') : '' }}
        {{ $name ? Str::of('name=')->append($name, '[product_id]') : '' }}
        {{ $tags ? Str::of('data-tags=')->append($tags) : '' }}
        {{ $attributes->has('x-init') ? '' : 'onchange=getProductSelected(this.id,this.value)' }}
        {{ $attributes->has('x-init') ? '' : 'x-init=initializeSelect2($el)' }}
        {{ $attributes->whereStartsWith('x-') }}
    >
        <option
            data-code=""
            data-category=""
        ></option>
        @foreach ($products as $product)
            <option
                value="{{ $product->id }}"
                {{ $selectedProductId == $product->id ? 'selected' : '' }}
                data-code="{{ $product->code ?? '' }}"
                data-category="{{ Str::of($product->productCategory->name)->replace('  ', ' ') }}"
            >
                {{ $product->name }}

                @if ($product->code)
                    ({{ $product->code }})
                @endif
            </option>
        @endforeach
        @if (!is_numeric($selectedProductId))
            <option
                value="{{ $selectedProductId }}"
                selected
                data-code="{{ $selectedProductId }}"
                data-category="{{ $selectedProductId }}"
            >{{ $selectedProductId }}</option>
        @endif
    </select>
</div>

<select
    id="original-select"
    class="is-hidden"
    onchange="getProductSelected(this.id, this.value)"
    {{ $tags ? Str::of('data-tags=')->append($tags) : '' }}
>
    <option
        data-code=""
        data-category=""
    ></option>
    @foreach ($products as $product)
        <option
            value="{{ $product->id }}"
            data-code="{{ $product->code ?? '' }}"
            data-category="{{ Str::of($product->productCategory->name)->replace('  ', ' ') }}"
        >
            {{ $product->name }}

            @if ($product->code)
                ({{ $product->code }})
            @endif
        </option>
    @endforeach
</select>

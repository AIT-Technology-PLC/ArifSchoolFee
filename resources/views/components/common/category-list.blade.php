<div class="select">
    <select {{ $attributes->whereStartsWith('x-') }}>
        <option
            value=""
            disabled
            selected
        > Select Category </option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    <div class="icon is-small is-left">
        <i class="fas fa-layer-group"></i>
    </div>
</div>

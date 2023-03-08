<select
    id="{{ $id }}"
    name="{{ $name }}"
    x-init="{{ $attributes->get('x-init') ?? 'initSelect2($el, "Category")' }}"
    class="{{ $attributes->get('class') }}"
    {{ $attributes->whereDoesntStartWith('class') }}
>
    @foreach ($categoriess as $category)
        <option></option>

        <option
            value="{{ $category->$value }}"
            {{ $selectedId == $category->$value ? 'selected' : '' }}
        >
            {{ $category->name }}
        </option>
    @endforeach
    <option value=" ">None</option>
</select>

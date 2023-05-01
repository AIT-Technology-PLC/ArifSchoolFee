<select
    id="{{ $id }}"
    name="{{ $name }}"
    x-init="{{ $attributes->get('x-init') ?? 'initSelect2($el, "Batch")' }}"
    class="{{ $attributes->get('class') }}"
>
    @foreach ($batches as $batch)
        <option></option>

        <option
            value="{{ $batch->$value }}"
            {{ $selectedId == $batch->$value ? 'selected' : '' }}
        >
            {{ $batch->batch_no }}
        </option>
    @endforeach
</select>

<select
    id="{{ $id }}"
    name="{{ $name }}"
    x-init="{{ $attributes->get('x-init') ?? 'initSelect2($el, "Contact")' }}"
    class="{{ $attributes->get('class') }}"
>
    @foreach ($contacts as $contact)
        <option></option>

        <option
            value="{{ $contact->$value }}"
            {{ $selectedId == $contact->$value ? 'selected' : '' }}
        >
            {{ $contact->name }}
        </option>
    @endforeach
    <option value=" ">None</option>
</select>

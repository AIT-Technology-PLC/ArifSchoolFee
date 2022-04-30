<select
    id="{{ $id }}"
    name="{{ $name }}"
    x-init="{{ $attributes->get('x-init') ?? 'initSelect2($el, "User")' }}"
    class="{{ $attributes->get('class') }}"
>
    @foreach ($users as $user)
        <option></option>

        <option
            value="{{ $user->$value }}"
            {{ $selectedId == $user->$value ? 'selected' : '' }}
        >
            {{ $user->name }}
        </option>
    @endforeach
    <option value=" ">None</option>
</select>

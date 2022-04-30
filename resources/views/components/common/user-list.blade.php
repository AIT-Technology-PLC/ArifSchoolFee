<select
    id="{{ $id }}"
    name="{{ $name }}"
    {{ $attributes->whereStartsWith('x-init') }}
    {{ $attributes->class([]) }}
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

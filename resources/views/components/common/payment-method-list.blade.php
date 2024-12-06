<x-forms.select
    class="is-fullwidth"
    id="{{ $id }}"
    name="{{ $name }}"
>
    <option selected disabled>Select Payment Method</option>

    @foreach ($paymentMethods as $paymentMethod)
        <option
            value="{{ $paymentMethod->$value }}"
        >
            {{ $paymentMethod->name }}
        </option>
    @endforeach
</x-forms.select>

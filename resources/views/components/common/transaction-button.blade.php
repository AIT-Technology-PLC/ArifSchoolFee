@props(['route', 'intention', 'action', 'icon', 'label'])

<form
    x-data="swal('{{ $action }}', '{{ $intention }}')"
    id="formOne"
    class="is-inline"
    action="{{ $route }}"
    method="post"
    novalidate
    @submit.prevent="open"
>
    @csrf
    <x-common.button
        tag="button"
        mode="button"
        label="{{ $label }}"
        icon="{{ $icon }}"
        {{ $attributes->merge(['class' => 'btn-purple is-outlined is-small']) }}
    />
</form>

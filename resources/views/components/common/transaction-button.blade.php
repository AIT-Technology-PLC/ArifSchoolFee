@props(['route', 'type', 'action', 'description' => '', 'icon', 'label'])

<form
    id="formOne"
    class="is-inline"
    action="{{ $route }}"
    method="post"
    novalidate
>
    @csrf
    <x-common.button
        tag="button"
        mode="button"
        label="{{ $label }}"
        icon="{{ $icon }}"
        data-type="{{ $type }}"
        data-action="{{ $action }}"
        data-description="{{ $description }}"
        {{ $attributes->merge(['class' => 'swal btn-purple is-outlined is-small']) }}
    />
</form>

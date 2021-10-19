@props(['route', 'type', 'action', 'description' => '', 'icon', 'label'])

<form id="formOne"
      class="is-inline"
      action="{{ $route }}"
      method="post"
      novalidate>
    @csrf
    <button data-type="{{ $type }}"
            data-action="{{ $action }}"
            data-description="{{ $description }}"
            class="swal button btn-purple is-outlined is-small">
        <x-common.icon name="{{ $icon }}" />
        <span> {{ $label }} </span>
    </button>
</form>

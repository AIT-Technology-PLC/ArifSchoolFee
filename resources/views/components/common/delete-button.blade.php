@props(['model', 'id'])

<form class="is-inline delete-form" action="{{ route($model . '.destroy', $id) }}" method="post">
    @csrf
    @method('DELETE')
    <button class="tag is-black has-text-white has-text-weight-medium is-pointer is-borderless" data-title="Delete Permanently">
        <x-common.icon name="fas fa-trash" />
        <span>Delete</span>
    </button>
</form>

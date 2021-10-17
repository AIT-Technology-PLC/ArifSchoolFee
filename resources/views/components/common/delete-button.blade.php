@props(['model', 'id'])

<form class="is-inline delete-form" action="{{ route($model . '.destroy', $id) }}" method="post">
    @csrf
    @method('DELETE')
    <button class="tag bg-brown has-text-white is-small has-text-weight-medium is-pointer" data-title="Delete Permanently" style="border: none">
        <span class="icon">
            <i class="fas fa-trash"></i>
        </span>
        <span>
            Delete
        </span>
    </button>
</form>

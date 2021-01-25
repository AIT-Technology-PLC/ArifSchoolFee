<form id="deleteForm{{ $id }}" class="is-inline" action="{{ route($model . '.destroy', $id) }}" method="post" data-delete="{{ $id }}">
    @csrf
    @method('DELETE')
    <button class="tag is-danger is-outlined is-small has-text-weight-medium is-pointer" data-title="Delete Permanently" style="border: none">
        <span class="icon">
            <i class="fas fa-trash"></i>
        </span>
        <span>
            Delete
        </span>
    </button>
</form>

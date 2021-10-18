@props(['route', 'id'])

<form class="is-inline delete-form" action="{{ route($route, $id) }}" method="post">
    @csrf
    @method('DELETE')
    <button class="tag is-black has-text-white has-text-weight-medium is-pointer is-borderless" data-title="Delete permanently">
        <x-common.icon name="fas fa-trash" />
        <span> Delete </span>
    </button>
</form>

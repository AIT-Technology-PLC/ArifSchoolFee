<a href="{{ route("{$model}.edit", $id) }}" data-title="Modify Product Data">
    <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
        <span class="icon">
            <i class="fas fa-pen-square"></i>
        </span>
        <span>
            Edit
        </span>
    </span>
</a>
<x-delete-button :model="$model" :id="$id" />

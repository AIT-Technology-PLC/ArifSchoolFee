@props(['route', 'id', 'model'])

<a href="{{ route($route, $id) }}" data-title="Modify {{ $model }} Data">
    <span class="tag is-white btn-green is-outlined has-text-weight-medium">
        <x-common.icon name="fas fa-pen-square" />
        <span> Edit </span>
    </span>
</a>

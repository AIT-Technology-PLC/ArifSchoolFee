@props(['route', 'id'])

<a href="{{ route($route, $id) }}" data-title="View details">
    <span class="tag is-white btn-purple is-outlined has-text-weight-medium">
        <x-common.icon name="fas fa-info-circle" />
        <span> Details </span>
    </span>
</a>

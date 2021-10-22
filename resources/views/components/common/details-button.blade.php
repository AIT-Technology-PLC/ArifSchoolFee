@props(['route', 'id'])

<x-common.button
    tag="a"
    href="{{ route($route, $id) }}"
    mode="tag"
    data-title="View details"
    icon="fas fa-info-circle"
    label="Details"
    class="is-white btn-purple is-outlined has-text-weight-medium is-not-underlined"
/>

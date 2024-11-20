@props(['route', 'id'])

<x-common.button
    tag="a"
    href="{{ route($route, $id) }}"
    mode="button"
    data-title="View details"
    icon="fas fa-info-circle"
    class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

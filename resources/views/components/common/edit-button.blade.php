@props(['route', 'id'])

<x-common.button
    tag="a"
    href="{{ route($route, $id) }}"
    mode="button"
    data-title="Edit data"
    icon="fas fa-pen-square"
    class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

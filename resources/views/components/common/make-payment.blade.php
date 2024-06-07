@props(['route', 'id'])

<x-common.button
    tag="a"
    href="{{ route($route, $id) }}"
    mode="button"
    data-title="Make Payment"
    icon="fas fa-money-bill"
    class="text-purple has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

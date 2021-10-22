@props(['route', 'id'])

<x-common.button tag="a"
                 href="{{ route($route, $id) }}"
                 mode="tag"
                 data-title="Edit data"
                 icon="fas fa-pen-square"
                 label="Edit"
                 class="is-white btn-green is-outlined has-text-weight-medium is-not-underlined" />

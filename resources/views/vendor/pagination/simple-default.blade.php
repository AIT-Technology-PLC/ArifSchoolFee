@if ($paginator->hasPages())
    <nav>
        <ul class="pagination is-justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        href="{{ $paginator->previousPageUrl() }}"
                        icon="fas fa-arrow-left-long"
                        data-title="Previous"
                        class="is-small is-outlined btn-green has-text-weight-bold p-2 mx-1"
                        disabled
                    />
                </li>
            @else
                <li>
                    <x-common.button
                        tag="a"
                        mode="button"
                        href="{{ $paginator->previousPageUrl() }}"
                        icon="fas fa-arrow-left-long"
                        data-title="Previous"
                        class="is-small is-outlined btn-green has-text-weight-bold p-2 mx-1"
                    />
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <x-common.button
                    tag="a"
                    mode="button"
                    href="{{ $paginator->nextPageUrl() }}"
                    icon="fas fa-arrow-right-long"
                    data-title="Next"
                    class="is-small is-outlined btn-green has-text-weight-bold p-2 mx-1"
                />
            @else
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        href="{{ $paginator->previousPageUrl() }}"
                        icon="fas fa-arrow-right-long"
                        data-title="Next"
                        class="is-small is-outlined btn-green has-text-weight-bold p-2 mx-1"
                        disabled
                    />
                </li>
            @endif
        </ul>
    </nav>
@endif

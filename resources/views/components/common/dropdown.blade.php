@props(['name', 'isLeft' => false])

@if ($slot->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'dropdown is-hoverable ' . ($isLeft ? 'is-left' : 'is-right')]) }}>
        <div class="dropdown-trigger">
            <button class="button is-small btn-green is-outlined">
                <span>{{ $name }}</span>
                <span class="icon is-small">
                    <i class="fas fa-ellipsis-v"></i>
                </span>
            </button>
        </div>
        <div class="dropdown-menu">
            <div
                class="dropdown-content"
                style="max-height: 185px; overflow-y: scroll"
            >
                {{ $slot }}
            </div>
        </div>
    </div>
@endif

@props(['name'])

<div class="dropdown is-right is-hoverable">
    <div class="dropdown-trigger">
        <button class="button is-small btn-green is-outlined">
            <span>{{ $name }}</span>
            <span class="icon is-small">
                <i class="fas fa-angle-down"></i>
            </span>
        </button>
    </div>
    <div class="dropdown-menu">
        <div class="dropdown-content">
            {{ $slot }}
        </div>
    </div>
</div>

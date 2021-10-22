@props(['tag', 'icon' => null, 'label' => null, 'mode' => null])

@if ($tag == 'button')
    <button {{ $attributes->class([
    'tag is-pointer is-borderless' => $mode == 'tag',
    'button' => $mode == 'button',
    '' => is_null($mode),
]) }}>
        @if (!is_null($icon))
            <x-common.icon name="{{ $icon }}" />
        @endif
        @if (!is_null($label))
            <span> {{ $label }} </span>
        @endif
        {{ $slot ?? '' }}
    </button>
@elseif($tag == 'a')
    <a {{ $attributes->class([
    'tag' => $mode == 'tag',
    'button' => $mode == 'button',
    '' => is_null($mode),
]) }}>
        @if (!is_null($icon))
            <x-common.icon name="{{ $icon }}" />
        @endif
        @if (!is_null($label))
            <span> {{ $label }} </span>
        @endif
        {{ $slot ?? '' }}
    </a>
@endif

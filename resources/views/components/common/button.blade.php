@props(['tag', 'icon' => null, 'label' => null, 'mode'])

@if ($tag == 'button')
    <button {{ $attributes->class([
    'tag is-pointer is-borderless' => $mode == 'tag',
    'button' => $mode == 'button',
]) }}>
        @if (!is_null($icon))
            <x-common.icon name="{{ $icon }}" />
        @endif
        @if (!is_null($label))
            <span> {{ $label }} </span>
        @endif
    </button>
@elseif($tag == 'a')
    <a {{ $attributes->class([
    'tag' => $mode == 'tag',
    'button' => $mode == 'button',
]) }}>
        @if (!is_null($icon))
            <x-common.icon name="{{ $icon }}" />
        @endif
        @if (!is_null($label))
            <span> {{ $label }} </span>
        @endif
    </a>
@endif

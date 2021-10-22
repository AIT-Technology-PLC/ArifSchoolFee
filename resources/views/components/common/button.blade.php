@props(['tag', 'icon', 'label', 'mode'])

@if ($tag == 'button')
    <button {{ $attributes->class([
    'tag is-pointer is-borderless' => $mode == 'tag',
    'button' => $mode == 'button',
]) }}>
        <x-common.icon name="{{ $icon }}" />
        <span> {{ $label }} </span>
    </button>
@elseif($tag == 'a')
    <a {{ $attributes->class([
    'tag' => $mode == 'tag',
    'button' => $mode == 'button',
]) }}>
        <x-common.icon name="{{ $icon }}" />
        <span> {{ $label }} </span>
    </a>
@endif

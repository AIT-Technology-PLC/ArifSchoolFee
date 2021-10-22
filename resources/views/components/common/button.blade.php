@props(['tag', 'icon', 'label', 'type'])

@if ($tag == 'button')
    <button {{ $attributes->class([
    'tag is-pointer is-borderless' => $type == 'tag',
    'button' => $type == 'button',
]) }}>
        <x-common.icon name="{{ $icon }}" />
        <span> {{ $label }} </span>
    </button>
@elseif($tag == 'a')
    <a {{ $attributes->class([
    'tag' => $type == 'tag',
    'button' => $type == 'button',
]) }}>
        <x-common.icon name="{{ $icon }}" />
        <span> {{ $label }} </span>
    </a>
@endif

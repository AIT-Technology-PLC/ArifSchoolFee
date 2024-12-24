@props(['label', 'alignment' => 'is-right', 'icon' => null])

<div {{ $attributes->merge(['class' => 'buttons ' . $alignment]) }}>
    <x-common.button
        tag="button"
        mode="button"
        id="assignButton"
        icon="{{ $icon ? 'fas ' . $icon : 'fas fa-save' }}"
        label="{{ $label }}" 
        class="bg-softblue has-text-white is-small m-5"
    />
</div>
@props(['alignment' => 'is-right'])

<div {{ $attributes->merge(['class' => 'buttons ' . $alignment]) }}>
    <x-common.button
        tag="button"
        mode="button"
        type="reset"
        icon="fas fa-times"
        label="Cancel"
        class="is-light text-blue"
    />

    <x-common.button
        tag="button"
        mode="button"
        id="saveButton"
        icon="fas fa-save"
        label="Save"
        class="bg-blue has-text-white"
    />
</div>

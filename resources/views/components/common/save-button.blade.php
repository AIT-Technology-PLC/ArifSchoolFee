@props(['alignment' => 'is-right'])

<div {{ $attributes->merge(['class' => 'buttons ' . $alignment]) }}>
    <button class="button is-light text-green" type="reset">
        <span class="icon">
            <i class="fas fa-times"></i>
        </span>
        <span>
            Cancel
        </span>
    </button>
    <button id="saveButton" class="button bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-save"></i>
        </span>
        <span>
            Save
        </span>
    </button>
</div>

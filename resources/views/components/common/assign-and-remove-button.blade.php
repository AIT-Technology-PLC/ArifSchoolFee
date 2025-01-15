@props(['alignment' => 'is-right', 'assignLabel' => 'Assign Fee', 'removeLabel' => 'Remove Fee'])

<div {{ $attributes->merge(['class' => 'buttons ' . $alignment]) }}>
    <x-common.button
        tag="button"
        mode="button"
        type="submit"
        name="action"
        value="assign"
        icon="fas fa-check"
        :label="$assignLabel"
        class="btn-softblue is-outlined text-blue is-small"
    />

    <x-common.button
        tag="button"
        mode="button"
        type="submit"
        name="action"
        value="remove"
        icon="fas fa-times"
        :label="$removeLabel"
        class="bg-softblue has-text-white is-small"
    />
</div>

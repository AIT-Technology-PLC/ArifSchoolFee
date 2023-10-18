<div
    class="modal is-active"
    x-bind:class="Alpine.store('openCreateSupplierModal') ? '' : 'is-hidden'"
    x-transition
    x-cloak
>
    <div
        class="modal-background"
        x-on:click="Alpine.store('openCreateSupplierModal', false)"
    ></div>
    <div class="modal-content">
        <x-content.header title="New Supplier" />
        <livewire:supplier-form />
    </div>
    <x-common.button
        type="button"
        tag="button"
        class="modal-close is-large"
        x-on:click="Alpine.store('openCreateSupplierModal', false)"
    />
</div>

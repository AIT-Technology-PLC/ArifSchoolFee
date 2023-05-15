<div
    class="modal is-active"
    x-bind:class="Alpine.store('openCreateCustomerModal') ? '' : 'is-hidden'"
    x-transition
    x-cloak
>
    <div
        class="modal-background"
        x-on:click="Alpine.store('openCreateCustomerModal', false)"
    ></div>
    <div class="modal-content">
        <x-content.header title="New Customer" />
        <livewire:customer-form />
    </div>
    <x-common.button
        type="button"
        tag="button"
        class="modal-close is-large"
        x-on:click="Alpine.store('openCreateCustomerModal', false)"
    />
</div>

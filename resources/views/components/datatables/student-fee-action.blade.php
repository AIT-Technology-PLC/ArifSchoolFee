@if (!$assignFeeMaster->isPaid())
    <x-common.button
        tag="a"
        mode="button"
        @click="$dispatch('open-fee-details-modal', { id: '{{ $assignFeeMaster->id }}' })"
        data-title="Add Fee"
        icon="fas fa-hand-holding-dollar"
        class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
    />
@endif

@if ($assignFeeMaster->isDueDatePassed())
    <x-common.button
        tag="a"
        href="{{ route('fee-reminder', $assignFeeMaster->id) }}"
        mode="button"
        data-title="Send Payment Code"
        icon="fas fa-receipt"
        class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
    />
@endif

@can('Update Collect Fee')
    @include('collect-fees.partials.fee-details', ['assignFeeMaster' => $assignFeeMaster])
@endcan

{{-- <div
    x-data="{ isHidden: true, modalId: null }"
    @open-fee-details-modal.window="if ($event.detail.id === '{{ $assignFeeMaster->id }}') { modalId = $event.detail.id; isHidden = false }"
    x-show="modalId === '{{ $assignFeeMaster->id }}'"
    class="modal"
    x-cloak
    x-transition
>
<div class="modal-background" @click="isHidden = true"></div> --}}
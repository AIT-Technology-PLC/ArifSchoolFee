<x-common.button
    tag="a"
    mode="button"
    @click="$dispatch('open-fee-details-modal')"
    data-title="Add Fee"
    icon="fas fa-hand-holding-dollar"
    class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

@if ($assignFeeMaster->feeMaster->due_date > now())
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
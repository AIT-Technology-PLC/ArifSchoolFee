@if (!$assignFeeMaster->isPaid() && !$assignFeeMaster->paymentTransactions()->pending()->count())
    <x-common.button
        tag="a"
        mode="button"
        @click="$dispatch('open-fee-details-modal', { id: '{{ $assignFeeMaster->id }}' })"
        data-title="Add Fee"
        icon="fas fa-hand-holding-dollar"
        class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
    />
@endif

@if (!$assignFeeMaster->isPaid() && !$assignFeeMaster->paymentTransactions()->pending()->count())
    <x-common.button
        tag="a"
        mode="button"
        @click="$dispatch('open-fee-reminder-modal', { id: '{{ $assignFeeMaster->id }}' })"
        data-title="Send Payment Code"
        icon="fas fa-bell"
        class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
    />
@endif

@include('collect-fees.partials.fee-details', ['assignFeeMaster' => $assignFeeMaster])

@include('collect-fees.partials.fee-reminder', ['assignFeeMaster' => $assignFeeMaster])
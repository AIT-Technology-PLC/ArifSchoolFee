@if ($transaction->transactionStatus)
    <span
        class="tag has-text-weight-bold"
        style="background: {{ $transaction->transactionStatus->bg_color }} !important;color: {{ $transaction->transactionStatus->text_color }} !important"
    >
        {{ $transaction->transactionStatus->name }}
    </span>
@else
    <span class="tag is-dark">
        No Status
    </span>
@endif

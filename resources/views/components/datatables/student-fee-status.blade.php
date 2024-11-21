@if ($assignFeeMaster->feePayments->count())
    <span class="tag bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Paid
        </span>
    </span>
@else
    <span class="tag bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-times-circle"></i>
        </span>
        <span>
            Unpaid
        </span>
    </span>
@endif

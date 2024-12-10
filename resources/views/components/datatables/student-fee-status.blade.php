@if (!$assignFeeMaster->feePayments->count() && $assignFeeMaster->paymentTransactions()->pending()->count())    
    <span class="tag bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-hourglass-half"></i>
        </span>
        <span>
            Pending
        </span>
    </span>
@elseif ($assignFeeMaster->feePayments->count() && !$assignFeeMaster->paymentTransactions()->pending()->count())
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

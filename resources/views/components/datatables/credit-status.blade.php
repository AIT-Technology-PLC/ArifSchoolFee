@if ($credit->isSettled())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Fully Settled
        </span>
    </span>
@elseif ($credit->settlementPercentage)
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            {{ number_format($credit->settlement_percentage, 2) }}%
        </span>
    </span>
@else
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            No Settlements
        </span>
    </span>
@endif

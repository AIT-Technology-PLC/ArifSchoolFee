@if ($purchase->isRejected())
    <span class="tag is-small bg-purple has-text-white">
        <x-common.icon name="fas fa-times-circle" />
        <span>
            Rejected
        </span>
    </span>
@elseif (!$purchase->isApproved())
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            Waiting Approval
        </span>
    </span>
@elseif ($purchase->isCancelled())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-times-circle"></i>
        </span>
        <span>
            Cancelled
        </span>
    </span>
@elseif ($purchase->isPurchased())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Purchased
        </span>
    </span>
@else
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            Approved (not Purchased)
        </span>
    </span>
@endif

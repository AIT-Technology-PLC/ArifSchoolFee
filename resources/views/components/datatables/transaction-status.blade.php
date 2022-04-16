@if ($transaction->pad->isCancellable())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-times-circle"></i>
        </span>
        <span>
            Cancelled
        </span>
    </span>
@endif

@if ($transaction->pad->isInventoryOperationAdd() && $transaction->isAdded())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Added
        </span>
    </span>
@endif

@if ($transaction->pad->isInventoryOperationSubtract() && $transaction->isSubtracted())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Subtracted
        </span>
    </span>
@endif

@if ($transaction->pad->isApprovable() && $transaction->isApproved())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            Approved
        </span>
    </span>
@else
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            Waiting Approval
        </span>
    </span>
@endif

@if ($transaction->pad->isInventoryOperationAdd() && $transaction->isAdded())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Added
        </span>
    </span>
@elseif ($transaction->pad->isInventoryOperationAdd() && $transaction->isPartiallyAdded())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Partially Added
        </span>
    </span>
@elseif ($transaction->pad->isInventoryOperationSubtract() && $transaction->isSubtracted())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Subtracted
        </span>
    </span>
@elseif ($transaction->pad->isInventoryOperationSubtract() && $transaction->isPartiallySubtracted())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Partially Subtracted
        </span>
    </span>
@elseif ($transaction->pad->isApprovable() && $transaction->isApproved())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            Approved
            {{ $transaction->pad->isInventoryOperationAdd() ? '(not Added)' : '' }}
            {{ $transaction->pad->isInventoryOperationSubtract() ? '(not Subtracted)' : '' }}
        </span>
    </span>
@elseif ($transaction->pad->isApprovable() && !$transaction->isApproved())
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            Waiting Approval
        </span>
    </span>
@endif

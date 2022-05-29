@if ($transaction->pad->isCancellable() && $transaction->isCancelled())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-times-circle"></i>
        </span>
        <span>
            Cancelled
        </span>
    </span>
@elseif ($transaction->pad->isClosableOnly() && $transaction->isClosed())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-times-circle"></i>
        </span>
        <span>
            Closed
        </span>
    </span>
@elseif ($transaction->pad->isInventoryOperationAdd() && $transaction->isAdded())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Added
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
@elseif ($transaction->pad->isApprovable() && $transaction->isApproved())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            Approved
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
@elseif ($transaction->pad->isClosable() && !$transaction->isClosed())
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            Open
        </span>
    </span>
@endif

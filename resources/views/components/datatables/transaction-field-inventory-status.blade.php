@if ($transaction->pad->isInventoryOperationAdd() && App\Models\TransactionField::isAdded($transaction, $line))
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Added
        </span>
    </span>
@elseif ($transaction->pad->isInventoryOperationSubtract() && App\Models\TransactionField::isSubtracted($transaction, $line))
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Subtracted
        </span>
    </span>
@else
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            Pending
        </span>
    </span>
@endif

@if (!$gdn->isApproved())
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            Waiting Approval
        </span>
    </span>
@elseif ($gdn->isCancelled())
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fas fa-times-circle"></i>
        </span>
        <span>
            Voided
        </span>
    </span>
@elseif ($gdn->isClosed())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-ban"></i>
        </span>
        <span>
            Closed
        </span>
    </span>
@elseif ($gdn->isSubtracted())
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
            Approved (not Subtracted)
        </span>
    </span>
@endif

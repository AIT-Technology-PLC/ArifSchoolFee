@if ($transfer->isAdded())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Added
        </span>
    </span>
@elseif ($transfer->isSubtracted())
    <span class="tag is-small bg-blue has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Subtracted
        </span>
    </span>
@elseif($transfer->isApproved())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            Approved (not Transferred)
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

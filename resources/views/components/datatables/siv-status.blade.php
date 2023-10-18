@if (!$siv->isApproved())
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            Waiting Approval
        </span>
    </span>
@elseif(userCompany()->canSivSubtract() && $siv->isSubtracted())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Subtracted
        </span>
    </span>
@else
    <span class="tag is-small has-text-white {{ userCompany()->canSivSubtract() ? 'bg-gold' : 'bg-green' }}">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Approved
        </span>
    </span>
@endif

@if ($subscription->isApproved())
    <span class="tag bg-lightgreen text-green has-text-weight-medium">
        <span class="icon">
            <i class="fas fa-dot-circle"></i>
        </span>
        <span>
            Approved
        </span>
    </span>
@else
    <span class="tag bg-purple has-text-white has-text-weight-medium">
        <span class="icon">
            <i class="fas fa-warning"></i>
        </span>
        <span>
            Waiting Approval
        </span>
    </span>
@endif

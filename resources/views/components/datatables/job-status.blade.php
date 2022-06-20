@if (!$job->isApproved())
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            Waiting Approval
        </span>
    </span>
@elseif ($job->jobCompletionRate < 100)
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-spinner"></i>
        </span>
        <span>
            {{ $job->jobCompletionRate }}
        </span>
    </span>
@else
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Done
        </span>
    </span>
@endif

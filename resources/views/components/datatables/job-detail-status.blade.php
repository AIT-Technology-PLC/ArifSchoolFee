@if (!$jobDetail->isStarted())
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            Not Started
        </span>
    </span>
@elseif (!$jobDetail->isCompleted())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-spinner"></i>
        </span>
        <span>
            {{ $jobDetail->completionRate }}
        </span>
    </span>
@else
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Completed
        </span>
    </span>
@endif

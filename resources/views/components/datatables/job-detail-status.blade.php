@if ($jobDetail->completionRate < 100)
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
            Done
        </span>
    </span>
@endif
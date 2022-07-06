@if ($job->isCompleted())
    <div class="has-text-centered">-</div>
@elseif ($job->forecastedCompletionRate == $job->jobCompletionRate)
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            On Schedule
        </span>
    </span>
@elseif ($job->forecastedCompletionRate < $job->jobCompletionRate)
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-double"></i>
        </span>
        <span>
            Ahead of Schedule
        </span>
    </span>
@else
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            Behind Schedule
        </span>
    </span>
@endif

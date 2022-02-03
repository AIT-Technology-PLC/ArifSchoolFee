@if ($reservation->isCancelled())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-times-circle"></i>
        </span>
        <span>
            Cancelled
        </span>
    </span>
@elseif ($reservation->isConverted())
    <span class="tag is-small bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            @if ($reservation->reservable->isSubtracted())
                Converted (Sold)
            @else
                Converted (Not Sold)
            @endif
        </span>
    </span>
@elseif ($reservation->isReserved())
    <span class="tag is-small bg-blue has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Reserved
        </span>
    </span>
@elseif($reservation->isApproved())
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            Approved (Not Reserved)
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

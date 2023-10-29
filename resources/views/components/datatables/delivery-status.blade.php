@if ($model->isFullyDelivered())
    <span class="tag is-small btn-green is-outlined has-text-white">
        <span class="icon">
            <i class="fas fa-check-double"></i>
        </span>
        <span>
            Fully Delivered
        </span>
    </span>
@elseif($model->isPartiallyDelivered())
    <span class="tag is-small btn-gold is-outlined has-text-white">
        <span class="icon">
            <i class="fas fa-check"></i>
        </span>
        <span>
            {{ $model->deliveredPercentage }}% Delivered 
        </span>
    </span>
@else
    <span class="tag is-small btn-purple is-outlined has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            Not Delivered
        </span>
    </span>
@endif

@if ($currency->isEnabled())
    <span class="tag bg-lightgreen text-green has-text-weight-medium">
        <span class="icon">
            <i class="fas fa-dot-circle"></i>
        </span>
        <span>
            Enabled
        </span>
    </span>
@else
    <span class="tag bg-purple has-text-white has-text-weight-medium">
        <span class="icon">
            <i class="fas fa-warning"></i>
        </span>
        <span>
            Disabled
        </span>
    </span>
@endif

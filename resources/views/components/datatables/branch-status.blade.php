@if ($branch->isActive())
    <span class="icon is-small text-green">
        <i class="fas fa-circle"></i>
    </span>
    <span class="text-green"> Active </span>
@else
    <span class="icon is-small text-purple">
        <i class="fas fa-circle"></i>
    </span>
    <span class="text-purple"> Not Active </span>
@endif

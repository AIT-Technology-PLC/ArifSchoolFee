@if ($company->isEnabled())
    <span class="icon is-small text-green">
        <i class="fas fa-circle-dot"></i>
    </span>
    <span class="text-green"> Active </span>
@else
    <span class="icon is-small text-purple">
        <i class="fas fa-circle-dot"></i>
    </span>
    <span class="text-purple"> Deactivated </span>
@endif

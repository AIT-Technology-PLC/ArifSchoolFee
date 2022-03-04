@if ($pad->isEnabled())
    <span class="icon is-small text-green">
        <i class="fas fa-circle"></i>
    </span>
    <span class="text-green"> Enabled </span>
@else
    <span class="icon is-small text-purple">
        <i class="fas fa-circle"></i>
    </span>
    <span class="text-purple"> Disabled </span>
@endif

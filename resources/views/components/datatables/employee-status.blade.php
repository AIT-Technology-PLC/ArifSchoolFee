@if ($employee->enabled)
    <span class="tag bg-softblue has-text-white">
        <span class="icon">
            <i class="fas fa-user-check"></i>
        </span>
        <span>
            Enabled
        </span>
    </span>
@else
    <span class="tag bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-user-alt-slash"></i>
        </span>
        <span>
            Disabled
        </span>
    </span>
@endif

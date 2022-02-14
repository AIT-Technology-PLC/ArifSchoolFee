@if ($tenderChecklistType->is_sensitive)
    <span class="tag btn-purple is-outlined has-text-white">
        <span class="icon">
            <i class="fas fa-lock"></i>
        </span>
        <span> Yes </span>
    </span>
@else
    <span class="tag btn-green is-outlined has-text-white">
        <span class="icon">
            <i class="fas fa-lock-open"></i>
        </span>
        <span> No </span>
    </span>
@endif

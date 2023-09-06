@if (!$costUpdate->isApproved() && !$costUpdate->isRejected())
    <span class="tag is-small bg-purple has-text-white">
        <x-common.icon name="fas fa-clock" />
        <span> Waiting Approval </span>
    </span>
@elseif ($costUpdate->isRejected())
    <span class="tag is-small bg-green has-text-white">
        <x-common.icon name="fas fa-check-circle" />
        <span> Rejected </span>
    </span>
@else
    <span class="tag is-small bg-gold has-text-white">
        <x-common.icon name="fas fa-exclamation-circle" />
        <span> Approved </span>
    </span>
@endif

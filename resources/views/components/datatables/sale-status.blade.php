@if ($sale->isCancelled())
    <span class="tag is-small bg-gold has-text-white">
        <x-common.icon name="fas fa-clock" />
        <span> Cancelled </span>
    </span>
@elseif (userCompany()->canSaleSubtract() && $sale->isSubtracted())
    <span class="tag is-small bg-green has-text-white">
        <x-common.icon name="fas fa-check-circle" />
        <span> Subtracted </span>
    </span>
@elseif (!userCompany()->canSaleSubtract() && $sale->isApproved())
    <span class="tag is-small bg-green has-text-white">
        <x-common.icon name="fas fa-check-circle" />
        <span> Approved </span>
    </span>
@elseif (userCompany()->canSaleSubtract() && $sale->isApproved())
    <span class="tag is-small bg-gold has-text-white">
        <x-common.icon name="fas fa-clock" />
        <span> Approved </span>
    </span>
@else
    <span class="tag is-small bg-purple has-text-white">
        <x-common.icon name="fas fa-exclamation-circle" />
        <span> Waiting Approval </span>
    </span>
@endif

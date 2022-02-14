@if ($proformaInvoice->isPending())
    <span class="tag bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            Pending
        </span>
    </span>
@endif
@if ($proformaInvoice->isConverted())
    <span class="tag bg-green has-text-white">
        <span class="icon">
            <i class="fas fa-check-circle"></i>
        </span>
        <span>
            Confirmed
        </span>
    </span>
@endif
@if ($proformaInvoice->isCancelled())
    <span class="tag bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-times-circle"></i>
        </span>
        <span>
            Cancelled
        </span>
    </span>
@endif

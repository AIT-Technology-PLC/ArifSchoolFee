@if ($gdnDetail->getStatusAttribute())
    <span class="tag is-small bg-gold has-text-white">
        <span class="icon">
            <i class="fas fa-money-bill"></i>
        </span>
        <span>
            Paid
        </span>
    </span>
@else
    <span class="tag is-small bg-purple has-text-white">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <span>
            Unpaid
        </span>
    </span>
@endif

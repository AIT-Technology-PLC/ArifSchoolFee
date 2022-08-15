@if ($amount == 0)
    <span class="tag is-small btn-green is-outlined has-text-white">
        {{ userCompany()->currency . '. ' . number_format($amount, 2) }}
    </span>
@else
    <span class="tag is-small btn-purple is-outlined has-text-white">
        {{ userCompany()->currency . '. ' . number_format($amount, 2) }}
    </span>
@endif

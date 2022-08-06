@if ($customer->credits()->sum('credit_amount') != $customer->credits()->sum('credit_amount_settled'))
    <a
        href="{{ route('customers.settle', $customer->id) }}"
        data-title="Settle Customer Credit"
    >
        <span class="tag is-white btn-purple is-outlined is-small text-purple has-text-weight-medium">
            <span class="icon">
                <i class="fas fa-money-check"></i>
            </span>
            <span>
                Settle Credit
            </span>
        </span>
    </a>
@endif

<x-common.edit-button
    route="customers.edit"
    :id="$customer->id"
/>
<x-common.delete-button
    route="customers.destroy"
    :id="$customer->id"
/>

<x-common.button
    tag="a"
    href="{{ route('assign-discount-fees.show', $feeDiscount->id) }}"
    mode="button"
    data-title="Assign Fee"
    icon="fas fa-tasks"
    class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

<x-common.action-buttons
    :buttons="['edit', 'delete']"
    model="fee-discounts"
    :id="$feeDiscount->id"
/>

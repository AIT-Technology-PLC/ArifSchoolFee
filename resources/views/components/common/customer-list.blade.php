<select
    x-data="customer"
    id="{{ $id }}"
    name="{{ $name }}"
    x-init="{{ $attributes->get('x-init') ?? 'select2Customer' }}"
    class="customer-list {{ $attributes->get('class') }}"
    {{ $attributes->whereDoesntStartWith('class') }}
>
    <option></option>
    @if (authUser()->can('Create Customer') && !request()->is('pads/*/transactions/*') && !request()->is('*/*/edit'))
        <option>Create New Customer</option>
    @endif
    @foreach ($customers as $customer)
        <option
            value="{{ $customer->$value }}"
            {{ $selectedId == $customer->$value ? 'selected' : '' }}
        >
            {{ $customer->company_name }}
        </option>
    @endforeach
    <option value=" ">None</option>
</select>

@push('scripts')
    <script>
        window.addEventListener('customer-created', event => {
            $(".customer-list").trigger("change", event.detail.customer);
        })

        document.addEventListener("alpine:init", () => {
            Alpine.store('openCreateCustomerModal', false);

            Alpine.data("customer", () => ({
                select2Customer() {
                    let select2 = initSelect2(this.$el, 'Customer');

                    select2.on("change", async (event, customer = null) => {
                        if (event.target.value == 'Create New Customer' && !customer) {
                            Alpine.store('openCreateCustomerModal', true);
                            return;
                        }

                        if (customer) {
                            Alpine.store('openCreateCustomerModal', false);
                            select2.append(new Option(customer.company_name, customer.{{ $value }}, false, false));
                            select2.val(customer.{{ $value }});
                            select2.trigger('change');
                        }
                    });
                },
            }));
        });
    </script>
@endpush

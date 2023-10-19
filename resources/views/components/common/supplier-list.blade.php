<select
    x-data="supplier"
    id="{{ $id }}"
    name="{{ $name }}"
    x-init="{{ $attributes->get('x-init') ?? 'select2Supplier' }}"
    class="supplier-list {{ $attributes->get('class') }}"
    {{ $attributes->whereDoesntStartWith('class') }}
>
    <option></option>
    @if (authUser()->can('Create Supplier') && !request()->is('pads/*/transactions/*') && !request()->is('*/*/edit'))
        <option>Create New Supplier</option>
    @endif
    @foreach ($suppliers as $supplier)
        <option
            value="{{ $supplier->$value }}"
            {{ $selectedId == $supplier->$value ? 'selected' : '' }}
        >
            {{ $supplier->company_name }}
        </option>
    @endforeach
    <option value=" ">None</option>
</select>

@push('scripts')
    <script>
        window.addEventListener('supplier-created', event => {
            $(".supplier-list").trigger("change", event.detail[0].supplier);
        })

        document.addEventListener("alpine:init", () => {
            Alpine.store('openCreateSupplierModal', false);

            Alpine.data("supplier", () => ({
                select2Supplier() {
                    let select2 = initSelect2(this.$el, 'Supplier');

                    select2.on("change", async (event, supplier = null) => {
                        if (event.target.value == 'Create New Supplier' && !supplier) {
                            Alpine.store('openCreateSupplierModal', true);
                            return;
                        }

                        if (supplier) {
                            Alpine.store('openCreateSupplierModal', false);
                            select2.append(new Option(supplier.company_name, supplier.{{ $value }}, false, false));
                            select2.val(supplier.{{ $value }});
                            select2.trigger('change');
                        }
                    });
                },
            }));
        });
    </script>
@endpush

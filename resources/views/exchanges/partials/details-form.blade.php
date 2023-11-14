<x-content.main
    x-data="exchangeDetail({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('exchange.*')) }})"
>
    <template
        x-for="(exchange, index) in exchanges"
        x-bind:key="index"
    >
        <div
            class="mx-3"
            @gdn-changed.window="gdnChanged(event.detail)"
            @sale-changed.window="saleChanged(event.detail)"
        >
            <x-forms.field class="has-addons mb-0 mt-5">
                <x-forms.control>
                    <span
                        class="tag bg-green has-text-white is-medium is-radiusless"
                        x-text="`Item ${index + 1}`"
                    ></span>
                </x-forms.control>
                <x-forms.control x-show="canAddOrRemoveItems">
                    <x-common.button
                        tag="button"
                        mode="tag"
                        type="button"
                        class="bg-lightgreen has-text-white is-medium is-radiusless"
                        x-on:click="remove(index)"
                    >
                        <x-common.icon
                            name="fas fa-times-circle"
                            class="text-green"
                        />
                    </x-common.button>
                </x-forms.control>
            </x-forms.field>
            <div class="box has-background-white-bis radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(exchange.product_id), 'is-4': Product.isBatchable(exchange.product_id) }"
                    >
                        <x-forms.label x-bind:for="`exchange[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="exchange.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), exchange.product_id, exchange.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`exchange[${index}][product_id]`"
                                    x-bind:name="`exchange[${index}][product_id]`"
                                    x-model="exchange.product_id"
                                    x-init="select2(index)"
                                    inventory-type
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`exchange.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-4"
                        x-show="Product.isBatchable(exchange.product_id)"
                    >
                        <x-forms.label x-bind:for="`exchange[${index}][merchandise_batch_id]`">
                            Batch No <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    class="merchandise-batches is-fullwidth"
                                    x-bind:id="`exchange[${index}][merchandise_batch_id]`"
                                    x-bind:name="`exchange[${index}][merchandise_batch_id]`"
                                    x-model="exchange.merchandise_batch_id"
                                ></x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`exchange.${index}.merchandise_batch_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(exchange.product_id), 'is-4': Product.isBatchable(exchange.product_id) }"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`exchange[${index}][warehouse_id]`">
                                To <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-init="$nextTick(() => { exchange.warehouse_id = $el.value })"
                                    x-bind:id="`exchange[${index}][warehouse_id]`"
                                    x-bind:name="`exchange[${index}][warehouse_id]`"
                                    x-model="exchange.warehouse_id"
                                    x-on:change="warehouseChanged(index)"
                                >
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            {{ ($gdnDetails['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                        >{{ $warehouse->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-warehouse"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`exchange.${index}.warehouse_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`exchange[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`exchange[${index}][quantity]`"
                                    x-bind:name="`exchange[${index}][quantity]`"
                                    x-model="exchange.quantity"
                                    x-bind:placeholder="Product.unitOfMeasurement(exchange.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`exchange.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`exchange[${index}][returned_quantity]`">
                            Returned Quantity <sup class="has-text-danger">*</sup>
                            @if (userCompany()->isInventoryCheckerEnabled())
                                <sup
                                    class="tag bg-lightpurple text-purple"
                                    x-show="exchange.returnableQuantity"
                                    x-text="`${exchange.returnableQuantity} ${Product.unitOfMeasurement(exchange.product_id)}`"
                                    x-bind:class="{ 'bg-lightpurple text-purple': parseFloat(exchange.returnableQuantity) <= 0, 'bg-lightgreen text-green': parseFloat(exchange.returnableQuantity) > 0 }"
                                >
                                </sup>
                            @endif
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`exchange[${index}][returned_quantity]`"
                                    x-bind:name="`exchange[${index}][returned_quantity]`"
                                    x-model="exchange.returned_quantity"
                                    x-bind:placeholder="Product.unitOfMeasurement(exchange.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`exchange.${index}.returned_quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`exchange[${index}][unit_price]`">
                            Unit Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName({{ userCompany()->isPriceBeforeTax() }}, exchange.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control
                                x-cloak
                                x-show="Product.prices(exchange.product_id).length && Product.prices(exchange.product_id).filter((price) => price.fixed_price == exchange.unit_price).length"
                                class="has-icons-left is-expanded"
                            >
                                <x-forms.select
                                    class="is-fullwidth"
                                    type="number"
                                    x-bind:id="`exchange[${index}][unit_price]`"
                                    x-bind:name="`exchange[${index}][unit_price]`"
                                    x-model="exchange.unit_price"
                                >
                                    <template
                                        x-for="(price , priceIndex) in Product.prices(exchange.product_id)"
                                        x-bind:key="priceIndex"
                                    >
                                        <option
                                            x-bind:value="price.fixed_price"
                                            x-text="price.name ? `${price.fixed_price} (${price.name})` : price.fixed_price"
                                            x-bind:selected="price.fixed_price == exchange.unit_price"
                                        ></option>
                                    </template>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`exchange.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control
                                x-show="!Product.prices(exchange.product_id).length || !Product.prices(exchange.product_id).filter((price) => price.fixed_price == exchange.unit_price).length"
                                class="has-icons-left is-expanded"
                            >
                                <x-forms.input
                                    x-bind:id="`exchange[${index}][unit_price]`"
                                    x-bind:name="`exchange[${index}][unit_price]`"
                                    x-model="exchange.unit_price"
                                    type="number"
                                    placeholder="Unit Price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`exchange.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label>
                            Total Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName(true, exchange.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceBeforeTax(exchange.unit_price, exchange.quantity, exchange.product_id).toFixed(2)"
                                    type="number"
                                    readonly
                                    disabled
                                />
                                <x-common.icon
                                    name="fas fa-money-check"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label>
                            Total Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName(false, exchange.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceAfterTax(exchange.unit_price, exchange.quantity, exchange.product_id).toFixed(2)"
                                    type="number"
                                    readonly
                                    disabled
                                />
                                <x-common.icon
                                    name="fas fa-file-invoice-dollar"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </template>

    @include('components.common.pricing', ['data' => 'exchanges'])

</x-content.main>


@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("exchangeMaster", (gdnId, saleId) => ({
                gdnId: "",
                saleId: "",

                async init() {
                    if (gdnId) {
                        const response = await axios.get(`/api/gdns/${gdnId}`);

                        this.gdn = response.data;
                    }

                    if (saleId) {
                        const response = await axios.get(`/api/sales/${saleId}`);

                        this.sale = response.data;
                    }
                },

                select2Gdn() {
                    let select2 = initSelect2(this.$el, 'Delivery Order');

                    select2.on("change", async (event) => {
                        const response = await axios.get(`/api/gdns/${event.target.value}`);
                        this.gdn = response.data;

                        window.dispatchEvent(new CustomEvent('gdn-changed', {
                            detail: this.gdn,
                        }));
                    });
                },
                select2Sale() {
                    let select2 = initSelect2(this.$el, 'Invoice');
                    select2.on("change", async (event) => {
                        const response = await axios.get(`/api/sales/${event.target.value}`);
                        this.sale = response.data;

                        window.dispatchEvent(new CustomEvent('sale-changed', {
                            detail: this.sale,
                        }));
                    });
                },
            }));

            Alpine.data("exchangeDetail", (exchange) => ({
                exchanges: [],

                async init() {
                    await Promise.all([Company.init(), Product.init({{ Js::from($products) }}).inventoryType(), MerchandiseBatch.init({{ Js::from($merchandiseBatches) }})]);

                    if (exchange.hasOwnProperty('exchange')) {
                        this.exchanges = exchange.exchange;

                        await Promise.resolve(this.exchanges.forEach((exchange) => exchange.product_category_id = Product.productCategoryId(exchange.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.exchanges.push({});
                },

                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event, haveData = false) => {
                        this.exchanges[index].product_id = event.target.value;

                        this.exchanges[index].product_category_id = Product.productCategoryId(this.exchanges[index].product_id);

                        if (Product.isBatchable(this.exchanges[index].product_id)) {
                            MerchandiseBatch.appendMerchandiseBatches(
                                this.getMerchandiseBatchesSelect(index),
                                this.exchanges[index].merchandise_batch_id,
                                MerchandiseBatch.where(this.exchanges[index].product_id, this.exchanges[index].warehouse_id)
                            );
                        }

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.exchanges[index].product_id, this.exchanges[index].product_category_id);

                            this.exchanges[index].unit_price = Product.prices(
                                this.exchanges[index].product_id
                            ).length ? Product.prices(
                                this.exchanges[index].product_id
                            )[0].fixed_price : "";
                        }

                    });
                },
                getSelect2(index) {
                    return $(".product-list").eq(index);
                },
                getMerchandiseBatchesSelect(index) {
                    return document.getElementsByClassName("merchandise-batches")[index].firstElementChild;
                },
                warehouseChanged(index) {
                    MerchandiseBatch.appendMerchandiseBatches(
                        this.getMerchandiseBatchesSelect(index),
                        this.exchanges[index].merchandise_batch_id,
                        MerchandiseBatch.where(this.exchanges[index].product_id, this.exchanges[index].warehouse_id),
                    )
                },
                async gdnChanged(gdn) {
                    this.exchanges = gdn.gdn_details;

                    await Promise.resolve(this.exchanges.forEach((exchange) => {
                        exchange.unit_price = exchange.originalUnitPrice;
                        exchange.product_category_id = Product.productCategoryId(exchange.product_id);
                        exchange.hasOwnProperty('returnableQuantity') && (exchange.quantity = 0)
                    }));

                    await Promise.resolve($(".product-list").trigger("change", [true]));
                },
                async saleChanged(sale) {
                    this.exchanges = sale.sale_details;

                    await Promise.resolve(this.exchanges.forEach((exchange) => {
                        exchange.unit_price = exchange.originalUnitPrice;
                        exchange.product_category_id = Product.productCategoryId(exchange.product_id);
                        exchange.hasOwnProperty('returnableQuantity') && (exchange.quantity = 0)
                    }));

                    await Promise.resolve($(".product-list").trigger("change", [true]));
                }
            }));
        });
    </script>
@endpush

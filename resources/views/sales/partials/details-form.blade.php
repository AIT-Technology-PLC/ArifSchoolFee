<x-content.main
    x-data="saleMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('sale.*')) }})"
>
    <template
        x-for="(sale, index) in sales"
        x-bind:key="index"
    >
        <div class="mx-3">
            <x-forms.field class="has-addons mb-0 mt-5">
                <x-forms.control>
                    <span
                        class="tag bg-green has-text-white is-medium is-radiusless"
                        x-text="`Item ${index + 1}`"
                    ></span>
                </x-forms.control>
                <x-forms.control>
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
                        class="column is-12"
                        x-bind:class="{
                            'is-12': !{{ userCompany()->canSaleSubtract() }} && (!Product.isBatchable(sale.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}),
                            'is-6': {{ userCompany()->canSaleSubtract() }} ^ (Product.isBatchable(sale.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }}),
                            'is-4': {{ userCompany()->canSaleSubtract() }} && Product.isBatchable(sale.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }}
                        }"
                    >
                        <x-forms.label x-bind:for="`sale[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 20%"
                            >
                                <x-common.category-list
                                    x-model="sale.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), sale.product_id, sale.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`sale[${index}][product_id]`"
                                    x-bind:name="`sale[${index}][product_id]`"
                                    x-model="sale.product_id"
                                    x-init="select2(index)"
                                    included-products="sales"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`sale.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (userCompany()->canSelectBatchNumberOnForms())
                        <div
                            class="column is-4"
                            x-bind:class="{ 'is-6': !{{ userCompany()->canSaleSubtract() }}, 'is-4': {{ userCompany()->canSaleSubtract() }} }"
                            x-show="Product.isBatchable(sale.product_id)"
                        >
                            <x-forms.label x-bind:for="`sale[${index}][merchandise_batch_id]`">
                                Batch No <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.select
                                        class="merchandise-batches is-fullwidth"
                                        x-bind:id="`sale[${index}][merchandise_batch_id]`"
                                        x-bind:name="`sale[${index}][merchandise_batch_id]`"
                                        x-model="sale.merchandise_batch_id"
                                        x-on:change="getInventoryLevel(index)"
                                    ></x-forms.select>
                                    <x-common.icon
                                        name="fas fa-th"
                                        class="is-small is-left"
                                    />
                                    <span
                                        class="help has-text-danger"
                                        x-text="$store.errors.getErrors(`sale.${index}.merchandise_batch_id`)"
                                    ></span>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    @if (userCompany()->canSaleSubtract())
                        <div
                            class="column is-6"
                            x-bind:class="{ 'is-6': !Product.isBatchable(sale.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(sale.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                        >
                            <x-forms.field>
                                <x-forms.label x-bind:for="`sale[${index}][warehouse_id]`">
                                    From <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        x-init="$nextTick(() => { sale.warehouse_id = $el.value })"
                                        x-bind:id="`sale[${index}][warehouse_id]`"
                                        x-bind:name="`sale[${index}][warehouse_id]`"
                                        x-model="sale.warehouse_id"
                                        x-on:change="warehouseChanged(index)"
                                    >
                                        @foreach ($warehouses as $warehouse)
                                            <option
                                                value="{{ $warehouse->id }}"
                                                {{ ($saleDetail['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                            >{{ $warehouse->name }}</option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-warehouse"
                                        class="is-small is-left"
                                    />
                                    <span
                                        class="help has-text-danger"
                                        x-text="$store.errors.getErrors(`sale.${index}.warehouse_id`)"
                                    ></span>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`sale[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                            @if (userCompany()->isInventoryCheckerEnabled())
                                <sup
                                    class="tag bg-lightpurple text-purple"
                                    x-show="sale.availableQuantity"
                                    x-text="sale.availableQuantity"
                                    x-bind:class="{ 'bg-lightpurple text-purple': parseFloat(sale.availableQuantity) <= 0, 'bg-lightgreen text-green': parseFloat(sale.availableQuantity) > 0 }"
                                >
                                </sup>
                            @endif
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`sale[${index}][quantity]`"
                                    x-bind:name="`sale[${index}][quantity]`"
                                    x-model="sale.quantity"
                                    type="number"
                                    x-bind:placeholder="Product.unitOfMeasurement(sale.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`sale.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`sale[${index}][unit_price]`">
                            Unit Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName({{ userCompany()->isPriceBeforeTax() }}, sale.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control
                                x-cloak
                                x-show="Product.prices(sale.product_id).length && Product.prices(sale.product_id).filter((price) => price.fixed_price == sale.unit_price).length"
                                class="has-icons-left is-expanded"
                            >
                                <x-forms.select
                                    class="is-fullwidth"
                                    type="number"
                                    x-bind:id="`sale[${index}][unit_price]`"
                                    x-bind:name="`sale[${index}][unit_price]`"
                                    x-init="sale.hasOwnProperty('originalUnitPrice') && (sale.unit_price = sale.originalUnitPrice)"
                                    x-model="sale.unit_price"
                                >
                                    <template
                                        x-for="(price , priceIndex) in Product.prices(sale.product_id)"
                                        x-bind:key="priceIndex"
                                    >
                                        <option
                                            x-bind:value="price.fixed_price"
                                            x-text="price.name ? `${price.fixed_price} (${price.name})` : price.fixed_price"
                                            x-bind:selected="price.fixed_price == sale.unit_price"
                                        ></option>
                                    </template>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`sale.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control
                                x-show="!Product.prices(sale.product_id).length || !Product.prices(sale.product_id).filter((price) => price.fixed_price == sale.unit_price).length"
                                class="has-icons-left is-expanded"
                            >
                                <x-forms.input
                                    x-bind:id="`sale[${index}][unit_price]`"
                                    x-bind:name="`sale[${index}][unit_price]`"
                                    x-init="sale.hasOwnProperty('originalUnitPrice') && (sale.unit_price = sale.originalUnitPrice)"
                                    x-model="sale.unit_price"
                                    type="number"
                                    placeholder="Unit Price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`sale.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label>
                            Total Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName(true, sale.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceBeforeTax(sale.unit_price, sale.quantity, sale.product_id).toFixed(2)"
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
                                x-text="Product.taxName(false, sale.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceAfterTax(sale.unit_price, sale.quantity, sale.product_id).toFixed(2)"
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
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`sale[${index}][description]`">
                                Additional Notes <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    x-bind:id="`sale[${index}][description]`"
                                    x-bind:name="`sale[${index}][description]`"
                                    x-model="sale.description"
                                    class="textarea pl-6"
                                    placeholder="Description or note to be taken"
                                >
                                </x-forms.textarea>
                                <x-common.icon
                                    name="fas fa-edit"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`sale.${index}.description`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </template>

    @include('components.common.pricing', ['data' => 'sales'])

    <x-common.button
        tag="button"
        type="button"
        mode="button"
        label="Add More Item"
        class="bg-purple has-text-white is-small ml-3 mt-6"
        x-on:click="add"
    />
</x-content.main>

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("saleMasterDetailForm", ({
                sale
            }) => ({
                sales: [],

                async init() {
                    await Promise.all([Company.init(), Product.init({{ Js::from($products) }}).forSale(), MerchandiseBatch.initAvailable({{ Js::from($merchandiseBatches) }})]);

                    if (sale) {
                        this.sales = sale;

                        await Promise.resolve(this.sales.forEach((sale) => sale.product_category_id = Product.productCategoryId(sale.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.sales.push({});
                },
                async remove(index) {
                    if (this.sales.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.sales.splice(index, 1));

                    await Promise.resolve(
                        this.sales.forEach((sale, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), sale.product_id, sale.product_category_id);

                                if (Product.isBatchable(this.sales[i].product_id) && Company.canSelectBatchNumberOnForms()) {
                                    MerchandiseBatch.appendMerchandiseBatches(
                                        this.getMerchandiseBatchesSelect(i),
                                        this.sales[i].merchandise_batch_id,
                                        MerchandiseBatch.where(this.sales[i].product_id, this.sales[i].warehouse_id),
                                    );
                                }
                            }
                        })
                    );

                    Pace.restart();
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);
                    let batches = [];

                    select2.on("change", (event, haveData = false) => {
                        this.sales[index].product_id = event.target.value;

                        haveData || (this.sales[index].merchandise_batch_id = null);

                        this.sales[index].product_category_id =
                            Product.productCategoryId(
                                this.sales[index].product_id
                            );

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.sales[index].product_id, this.sales[index].product_category_id);

                            this.sales[index].unit_price = Product.prices(
                                this.sales[index].product_id
                            ).length ? Product.prices(
                                this.sales[index].product_id
                            )[0].fixed_price : "";
                        }

                        this.getInventoryLevel(index);

                        if (!Product.isBatchable(this.sales[index].product_id) || !Company.canSelectBatchNumberOnForms()) {
                            return;
                        }

                        MerchandiseBatch.appendMerchandiseBatches(
                            this.getMerchandiseBatchesSelect(index),
                            this.sales[index].merchandise_batch_id,
                            MerchandiseBatch.where(this.sales[index].product_id, this.sales[index].warehouse_id),
                        );

                        if (this.sales[index].product_id && this.sales[index].warehouse_id) {
                            batches = MerchandiseBatch.where(this.sales[index].product_id, this.sales[index].warehouse_id);
                        }

                        if (batches.length <= 1) {
                            this.sales[index].merchandise_batch_id = batches[0]?.id;
                        }
                    });
                },
                getSelect2(index) {
                    return $(".product-list").eq(index);
                },
                getMerchandiseBatchesSelect(index) {
                    return document.getElementsByClassName("merchandise-batches")[index].firstElementChild;
                },
                async getInventoryLevel(index) {
                    if (!Company.isInventoryCheckerEnabled() || !this.sales[index].product_id || !this.sales[index].warehouse_id) {
                        return;
                    }

                    this.sales[index].availableQuantity = null;

                    if (Product.isBatchable(this.sales[index].product_id) && this.sales[index].merchandise_batch_id) {
                        let merchandiseBatch = MerchandiseBatch.whereBatchId(this.sales[index].merchandise_batch_id);
                        this.sales[index].availableQuantity = merchandiseBatch?.quantity + " " + Product.whereProductId(this.sales[index].product_id)?.unit_of_measurement;
                        return;
                    }

                    await Merchandise.init(this.sales[index].product_id, this.sales[index].warehouse_id);

                    this.sales[index].availableQuantity = Merchandise.merchandise;
                },
                warehouseChanged(index) {
                    if (!Product.isBatchable(this.sales[index].product_id) || !Company.canSelectBatchNumberOnForms()) {
                        this.getInventoryLevel(index);
                        return;
                    }

                    let batches = [];

                    this.sales[index].merchandise_batch_id = null

                    MerchandiseBatch.appendMerchandiseBatches(
                        this.getMerchandiseBatchesSelect(index),
                        this.sales[index].merchandise_batch_id,
                        MerchandiseBatch.where(this.sales[index].product_id, this.sales[index].warehouse_id),
                    );

                    if (this.sales[index].product_id && this.sales[index].warehouse_id) {
                        batches = MerchandiseBatch.where(this.sales[index].product_id, this.sales[index].warehouse_id);
                    }

                    if (batches.length <= 1) {
                        this.sales[index].merchandise_batch_id = batches[0]?.id;
                    }
                }
            }));
        });
    </script>
@endpush

<x-content.main
    x-data="gdnMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('gdn.*')) }})"
>
    <template
        x-for="(gdn, index) in gdns"
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
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(gdn.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(gdn.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                    >
                        <x-forms.label x-bind:for="`gdn[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="gdn.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), gdn.product_id, gdn.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`gdn[${index}][product_id]`"
                                    x-bind:name="`gdn[${index}][product_id]`"
                                    x-model="gdn.product_id"
                                    x-init="select2(index)"
                                    :includedProducts="['sales']"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`gdn.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (userCompany()->canSelectBatchNumberOnForms())
                        <div
                            class="column is-4"
                            x-show="Product.isBatchable(gdn.product_id)"
                        >
                            <x-forms.label x-bind:for="`gdn[${index}][merchandise_batch_id]`">
                                Batch No <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.select
                                        class="merchandise-batches is-fullwidth"
                                        x-bind:id="`gdn[${index}][merchandise_batch_id]`"
                                        x-bind:name="`gdn[${index}][merchandise_batch_id]`"
                                        x-model="gdn.merchandise_batch_id"
                                    ></x-forms.select>
                                    <x-common.icon
                                        name="fas fa-th"
                                        class="is-small is-left"
                                    />
                                    <span
                                        class="help has-text-danger"
                                        x-text="$store.errors.getErrors(`gdn.${index}.merchandise_batch_id`)"
                                    ></span>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(gdn.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(gdn.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`gdn[${index}][warehouse_id]`">
                                From <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-init="$nextTick(() => { gdn.warehouse_id = $el.value })"
                                    x-bind:id="`gdn[${index}][warehouse_id]`"
                                    x-bind:name="`gdn[${index}][warehouse_id]`"
                                    x-model="gdn.warehouse_id"
                                    x-on:change="warehouseChanged(index)"
                                >
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            {{ ($gdnDetail['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                        >{{ $warehouse->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-warehouse"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`gdn.${index}.warehouse_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`gdn[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                            @if (userCompany()->isInventoryCheckerEnabled())
                                <sup
                                    class="tag bg-lightpurple text-purple"
                                    x-show="gdn.availableQuantity"
                                    x-text="gdn.availableQuantity"
                                    x-bind:class="{ 'bg-lightpurple text-purple': parseFloat(gdn.availableQuantity) <= 0, 'bg-lightgreen text-green': parseFloat(gdn.availableQuantity) > 0 }"
                                >
                                </sup>
                            @endif
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`gdn[${index}][quantity]`"
                                    x-bind:name="`gdn[${index}][quantity]`"
                                    x-model="gdn.quantity"
                                    type="number"
                                    x-bind:placeholder="Product.unitOfMeasurement(gdn.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`gdn.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`gdn[${index}][unit_price]`">
                            Unit Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName({{ userCompany()->isPriceBeforeTax() }}, gdn.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control
                                x-cloak
                                x-show="Product.prices(gdn.product_id).length && Product.prices(gdn.product_id).filter((price) => price.fixed_price == gdn.unit_price).length"
                                class="has-icons-left is-expanded"
                            >
                                <x-forms.select
                                    class="is-fullwidth"
                                    type="number"
                                    x-bind:id="`gdn[${index}][unit_price]`"
                                    x-bind:name="`gdn[${index}][unit_price]`"
                                    x-init="gdn.hasOwnProperty('originalUnitPrice') && (gdn.unit_price = gdn.originalUnitPrice)"
                                    x-model="gdn.unit_price"
                                >
                                    <template
                                        x-for="(price , priceIndex) in Product.prices(gdn.product_id)"
                                        x-bind:key="priceIndex"
                                    >
                                        <option
                                            x-bind:value="price.fixed_price"
                                            x-text="price.name ? `${price.fixed_price} (${price.name})` : price.fixed_price"
                                            x-bind:selected="price.fixed_price == gdn.unit_price"
                                        ></option>
                                    </template>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`gdn.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control
                                x-show="!Product.prices(gdn.product_id).length || !Product.prices(gdn.product_id).filter((price) => price.fixed_price == gdn.unit_price).length"
                                class="has-icons-left is-expanded"
                            >
                                <x-forms.input
                                    x-bind:id="`gdn[${index}][unit_price]`"
                                    x-bind:name="`gdn[${index}][unit_price]`"
                                    x-init="gdn.hasOwnProperty('originalUnitPrice') && (gdn.unit_price = gdn.originalUnitPrice)"
                                    x-model="gdn.unit_price"
                                    type="number"
                                    placeholder="Unit Price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`gdn.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label>
                            Total Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName(true, gdn.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceBeforeTax(gdn.unit_price, gdn.quantity, gdn.product_id, gdn.discount).toFixed(2)"
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
                                x-text="Product.taxName(false, gdn.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceAfterTax(gdn.unit_price, gdn.quantity, gdn.product_id, gdn.discount).toFixed(2)"
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
                    <div class="column is-6 {{ userCompany()->isDiscountBeforeTax() ? '' : 'is-hidden' }}">
                        <x-forms.label x-bind:for="`gdn[${index}][discount]`">
                            Discount <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`gdn[${index}][discount]`"
                                    x-bind:name="`gdn[${index}][discount]`"
                                    x-model="gdn.discount"
                                    type="number"
                                    placeholder="Discount in Percentage"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`gdn.${index}.discount`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`gdn[${index}][description]`">
                                Additional Notes <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    x-bind:id="`gdn[${index}][description]`"
                                    x-bind:name="`gdn[${index}][description]`"
                                    x-model="gdn.description"
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
                                    x-text="$store.errors.getErrors(`gdn.${index}.description`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </template>

    @include('components.common.pricing', ['data' => 'gdns'])

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
            Alpine.data("gdnMasterDetailForm", ({
                gdn
            }) => ({
                gdns: [],

                async init() {
                    await Promise.all([Company.init(), Product.init({{ Js::from($products) }}).forSale(), MerchandiseBatch.initAvailable()]);

                    if (gdn) {
                        this.gdns = gdn;

                        await Promise.resolve(this.gdns.forEach((gdn) => gdn.product_category_id = Product.productCategoryId(gdn.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.gdns.push({});
                },
                async remove(index) {
                    if (this.gdns.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.gdns.splice(index, 1));

                    await Promise.resolve(
                        this.gdns.forEach((gdn, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), gdn.product_id, gdn.product_category_id);

                                if (Product.isBatchable(this.gdns[i].product_id) && Company.canSelectBatchNumberOnForms()) {
                                    MerchandiseBatch.appendMerchandiseBatches(
                                        this.getMerchandiseBatchesSelect(i),
                                        this.gdns[i].merchandise_batch_id,
                                        MerchandiseBatch.where(this.gdns[i].product_id, this.gdns[i].warehouse_id),
                                    );
                                }
                            }
                        })
                    );

                    Pace.restart();
                },
                async select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", async (event, haveData = false) => {
                        this.gdns[index].product_id = event.target.value;

                        this.gdns[index].product_category_id =
                            Product.productCategoryId(
                                this.gdns[index].product_id
                            );

                        if (Product.isBatchable(this.gdns[index].product_id) && Company.canSelectBatchNumberOnForms()) {
                            MerchandiseBatch.appendMerchandiseBatches(
                                this.getMerchandiseBatchesSelect(index),
                                this.gdns[index].merchandise_batch_id,
                                MerchandiseBatch.where(this.gdns[index].product_id, this.gdns[index].warehouse_id),
                            );
                        }

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.gdns[index].product_id, this.gdns[index].product_category_id);

                            this.gdns[index].unit_price = Product.prices(
                                this.gdns[index].product_id
                            ).length ? Product.prices(
                                this.gdns[index].product_id
                            )[0].fixed_price : "";
                        }

                        this.getInventoryLevel(index)
                    });
                },
                getSelect2(index) {
                    return $(".product-list").eq(index);
                },
                getMerchandiseBatchesSelect(index) {
                    return document.getElementsByClassName("merchandise-batches")[index].firstElementChild;
                },
                async getInventoryLevel(index) {
                    if (Company.isInventoryCheckerEnabled() && this.gdns[index].product_id && this.gdns[index].warehouse_id) {
                        await Merchandise.init(this.gdns[index].product_id, this.gdns[index].warehouse_id);
                        this.gdns[index].availableQuantity = Merchandise.merchandise;
                    }
                },
                warehouseChanged(index) {
                    this.getInventoryLevel(index);

                    MerchandiseBatch.appendMerchandiseBatches(
                        this.getMerchandiseBatchesSelect(index),
                        this.gdns[index].merchandise_batch_id,
                        MerchandiseBatch.where(this.gdns[index].product_id, this.gdns[index].warehouse_id),
                    )
                }
            }));
        });
    </script>
@endpush

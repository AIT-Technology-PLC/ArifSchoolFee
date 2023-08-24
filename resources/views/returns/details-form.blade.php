<x-content.main
    x-data="returnDetail({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('return.*')) }})"
>
    <template
        x-for="(returnn, index) in returns"
        x-bind:key="index"
    >
        <div
            class="mx-3"
            @gdn-changed.window="gdnChanged(event.detail)"
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
                        x-bind:class="{ 'is-6': !Product.isBatchable(returnn.product_id), 'is-4': Product.isBatchable(returnn.product_id) }"
                    >
                        <x-forms.label x-bind:for="`return[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="returnn.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), returnn.product_id, returnn.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`return[${index}][product_id]`"
                                    x-bind:name="`return[${index}][product_id]`"
                                    x-model="returnn.product_id"
                                    x-init="select2(index)"
                                    :type="['Raw Material', 'Finished Goods']"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`return.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-4"
                        x-show="Product.isBatchable(returnn.product_id)"
                    >
                        <x-forms.label x-bind:for="`return[${index}][merchandise_batch_id]`">
                            Batch No <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    class="merchandise-batches is-fullwidth"
                                    x-bind:id="`return[${index}][merchandise_batch_id]`"
                                    x-bind:name="`return[${index}][merchandise_batch_id]`"
                                    x-model="returnn.merchandise_batch_id"
                                ></x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`return.${index}.merchandise_batch_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(returnn.product_id), 'is-4': Product.isBatchable(returnn.product_id) }"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`return[${index}][warehouse_id]`">
                                To <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-init="$nextTick(() => { returnn.warehouse_id = $el.value })"
                                    x-bind:id="`return[${index}][warehouse_id]`"
                                    x-bind:name="`return[${index}][warehouse_id]`"
                                    x-model="returnn.warehouse_id"
                                    x-on:change="warehouseChanged(index)"
                                >
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            {{ ($returnDetails['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                        >{{ $warehouse->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-warehouse"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`return.${index}.warehouse_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`return[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                            <sup
                                class="tag bg-lightpurple text-purple"
                                x-show="returnn.returnableQuantity"
                                x-text="`${returnn.returnableQuantity} ${Product.unitOfMeasurement(returnn.product_id)}`"
                                x-bind:class="{ 'bg-lightpurple text-purple': parseFloat(returnn.returnableQuantity) <= 0, 'bg-lightgreen text-green': parseFloat(returnn.returnableQuantity) > 0 }"
                            >
                            </sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`return[${index}][quantity]`"
                                    x-bind:name="`return[${index}][quantity]`"
                                    x-model="returnn.quantity"
                                    x-bind:placeholder="Product.unitOfMeasurement(returnn.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`return.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`return[${index}][unit_price]`">
                            Unit Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName({{ userCompany()->isPriceBeforeTax() }}, returnn.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control
                                x-cloak
                                x-show="Product.prices(returnn.product_id).length && Product.prices(returnn.product_id).filter((price) => price.fixed_price == returnn.unit_price).length"
                                class="has-icons-left is-expanded"
                            >
                                <x-forms.select
                                    class="is-fullwidth"
                                    type="number"
                                    x-bind:id="`return[${index}][unit_price]`"
                                    x-bind:name="`return[${index}][unit_price]`"
                                    x-model="returnn.unit_price"
                                >
                                    <template
                                        x-for="(price , priceIndex) in Product.prices(returnn.product_id)"
                                        x-bind:key="priceIndex"
                                    >
                                        <option
                                            x-bind:value="price.fixed_price"
                                            x-text="price.name ? `${price.fixed_price} (${price.name})` : price.fixed_price"
                                            x-bind:selected="price.fixed_price == returnn.unit_price"
                                        ></option>
                                    </template>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`return.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control
                                x-show="!Product.prices(returnn.product_id).length || !Product.prices(returnn.product_id).filter((price) => price.fixed_price == returnn.unit_price).length"
                                class="has-icons-left is-expanded"
                            >
                                <x-forms.input
                                    x-bind:id="`return[${index}][unit_price]`"
                                    x-bind:name="`return[${index}][unit_price]`"
                                    x-model="returnn.unit_price"
                                    type="number"
                                    placeholder="Unit Price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`return.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label>
                            Total Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName(true, returnn.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceBeforeTax(returnn.unit_price, returnn.quantity, returnn.product_id).toFixed(2)"
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
                                x-text="Product.taxName(false, returnn.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceAfterTax(returnn.unit_price, returnn.quantity, returnn.product_id).toFixed(2)"
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
                            <x-forms.label x-bind:for="`return[${index}][description]`">
                                Additional Notes <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    x-bind:id="`return[${index}][description]`"
                                    x-bind:name="`return[${index}][description]`"
                                    x-model="returnn.description"
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
                                    x-text="$store.errors.getErrors(`return.${index}.description`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </template>

    @include('components.common.pricing', ['data' => 'returns'])

    <x-common.button
        tag="button"
        type="button"
        mode="button"
        label="Add More Item"
        class="bg-purple has-text-white is-small ml-3 mt-6"
        x-show="canAddOrRemoveItems"
        x-on:click="add"
    />

</x-content.main>

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("returnMaster", (gdnId) => ({
                gdn: "",
                isShowCustomerSelect: true,

                async init() {
                    if (gdnId) {
                        this.isShowCustomerSelect = false;

                        const response = await axios.get(`/api/gdns/${gdnId}`);

                        this.gdn = response.data;
                    }
                },

                select2Gdn() {
                    let select2 = initSelect2(this.$el, 'Delivery Order');

                    select2.on("change", async (event) => {
                        const response = await axios.get(`/api/gdns/${event.target.value}`);
                        this.gdn = response.data;

                        window.dispatchEvent(new CustomEvent('gdn-changed', {
                            detail: this.gdn
                        }));

                        this.isShowCustomerSelect = false;
                    });
                },
            }));

            Alpine.data("returnDetail", (returnn) => ({
                returns: [],
                canAddOrRemoveItems: true,

                async init() {
                    await Promise.all([Company.init(), Product.init({{ Js::from($products) }}).inventoryType(), MerchandiseBatch.init()]);

                    if (returnn.hasOwnProperty('return')) {
                        this.returns = returnn.return;

                        await Promise.resolve(this.returns.forEach((returnn) => returnn.product_category_id = Product.productCategoryId(returnn.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.returns.push({});
                },
                add() {
                    this.returns.push({});
                },
                async remove(index) {
                    if (this.returns.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.returns.splice(index, 1));

                    await Promise.resolve(
                        this.returns.forEach((returnn, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), returnn.product_id, returnn.product_category_id);

                                if (Product.isBatchable(this.returns[i].product_id) && Company.canSelectBatchNumberOnForms()) {
                                    MerchandiseBatch.appendMerchandiseBatches(
                                        this.getMerchandiseBatchesSelect(i),
                                        this.returns[i].merchandise_batch_id,
                                        MerchandiseBatch.where(this.returns[i].product_id, this.returns[i].warehouse_id)
                                    );
                                }
                            }
                        })
                    );

                    Pace.restart();
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event, haveData = false) => {
                        this.returns[index].product_id = event.target.value;

                        this.returns[index].product_category_id = Product.productCategoryId(this.returns[index].product_id);

                        if (Product.isBatchable(this.returns[index].product_id)) {
                            MerchandiseBatch.appendMerchandiseBatches(
                                this.getMerchandiseBatchesSelect(index),
                                this.returns[index].merchandise_batch_id,
                                MerchandiseBatch.where(this.returns[index].product_id, this.returns[index].warehouse_id)
                            );
                        }

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.returns[index].product_id, this.returns[index].product_category_id);

                            this.returns[index].unit_price = Product.prices(
                                this.returns[index].product_id
                            ).length ? Product.prices(
                                this.returns[index].product_id
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
                        this.returns[index].merchandise_batch_id,
                        MerchandiseBatch.where(this.returns[index].product_id, this.returns[index].warehouse_id),
                    )
                },
                async gdnChanged(gdn) {
                    this.returns = gdn.gdn_details;

                    await Promise.resolve(this.returns.forEach((returnn) => {
                        returnn.unit_price = returnn.originalUnitPrice;
                        returnn.product_category_id = Product.productCategoryId(returnn.product_id);
                        returnn.hasOwnProperty('returnableQuantity') && (returnn.quantity = 0)
                    }));

                    await Promise.resolve($(".product-list").trigger("change", [true]));

                    this.canAddOrRemoveItems = false;
                }
            }));
        });
    </script>
@endpush

<x-content.main
    x-data="reservationMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('reservation.*')) }})"
>
    <template
        x-for="(reservation, index) in reservations"
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
                        x-bind:class="{ 'is-6': !Product.isBatchable(reservation.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(reservation.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                    >
                        <x-forms.label x-bind:for="`reservation[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="reservation.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), reservation.product_id, reservation.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`reservation[${index}][product_id]`"
                                    x-bind:name="`reservation[${index}][product_id]`"
                                    x-model="reservation.product_id"
                                    x-init="select2(index)"
                                    included-products="sales"
                                    :type="['Raw Material', 'Finished Goods']"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`reservation.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (userCompany()->canSelectBatchNumberOnForms())
                        <div
                            class="column is-4"
                            x-show="Product.isBatchable(reservation.product_id)"
                        >
                            <x-forms.label x-bind:for="`reservation[${index}][merchandise_batch_id]`">
                                Batch No <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.select
                                        class="merchandise-batches is-fullwidth"
                                        x-bind:id="`reservation[${index}][merchandise_batch_id]`"
                                        x-bind:name="`reservation[${index}][merchandise_batch_id]`"
                                        x-model="reservation.merchandise_batch_id"
                                        x-on:change="getInventoryLevel(index)"
                                    ></x-forms.select>
                                    <x-common.icon
                                        name="fas fa-th"
                                        class="is-small is-left"
                                    />
                                    <span
                                        class="help has-text-danger"
                                        x-text="$store.errors.getErrors(`reservation.${index}.merchandise_batch_id`)"
                                    ></span>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(reservation.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(reservation.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`reservation[${index}][warehouse_id]`">
                                From <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-init="$nextTick(() => { reservation.warehouse_id = $el.value })"
                                    x-bind:id="`reservation[${index}][warehouse_id]`"
                                    x-bind:name="`reservation[${index}][warehouse_id]`"
                                    x-model="reservation.warehouse_id"
                                    x-on:change="warehouseChanged(index)"
                                >
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            {{ ($reservationDetail['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                        >{{ $warehouse->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-warehouse"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`reservation.${index}.warehouse_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`reservation[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                            @if (userCompany()->isInventoryCheckerEnabled())
                                <sup
                                    class="tag bg-lightpurple text-purple"
                                    x-show="reservation.availableQuantity"
                                    x-text="reservation.availableQuantity"
                                    x-bind:class="{ 'bg-lightpurple text-purple': parseFloat(reservation.availableQuantity) <= 0, 'bg-lightgreen text-green': parseFloat(reservation.availableQuantity) > 0 }"
                                >
                                </sup>
                            @endif
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`reservation[${index}][quantity]`"
                                    x-bind:name="`reservation[${index}][quantity]`"
                                    x-model="reservation.quantity"
                                    type="number"
                                    x-bind:placeholder="Product.unitOfMeasurement(reservation.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`reservation.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`reservation[${index}][unit_price]`">
                            Unit Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName({{ userCompany()->isPriceBeforeTax() }}, reservation.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control
                                x-cloak
                                x-show="Product.prices(reservation.product_id).length && Product.prices(reservation.product_id).filter((price) => price.fixed_price == reservation.unit_price).length"
                                class="has-icons-left is-expanded"
                            >
                                <x-forms.select
                                    class="is-fullwidth"
                                    type="number"
                                    x-bind:id="`reservation[${index}][unit_price]`"
                                    x-bind:name="`reservation[${index}][unit_price]`"
                                    x-init="reservation.hasOwnProperty('originalUnitPrice') && (reservation.unit_price = reservation.originalUnitPrice)"
                                    x-model="reservation.unit_price"
                                >
                                    <template
                                        x-for="(price , priceIndex) in Product.prices(reservation.product_id)"
                                        x-bind:key="priceIndex"
                                    >
                                        <option
                                            x-bind:value="price.fixed_price"
                                            x-text="price.name ? `${price.fixed_price} (${price.name})` : price.fixed_price"
                                            x-bind:selected="price.fixed_price == reservation.unit_price"
                                        ></option>
                                    </template>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`reservation.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control
                                x-show="!Product.prices(reservation.product_id).length || !Product.prices(reservation.product_id).filter((price) => price.fixed_price == reservation.unit_price).length"
                                class="has-icons-left is-expanded"
                            >
                                <x-forms.input
                                    x-bind:id="`reservation[${index}][unit_price]`"
                                    x-bind:name="`reservation[${index}][unit_price]`"
                                    x-init="reservation.hasOwnProperty('originalUnitPrice') && (reservation.unit_price = reservation.originalUnitPrice)"
                                    x-model="reservation.unit_price"
                                    type="number"
                                    placeholder="Unit Price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`reservation.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label>
                            Total Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName(true, reservation.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceBeforeTax(reservation.unit_price, reservation.quantity, reservation.product_id, reservation.discount).toFixed(2)"
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
                                x-text="Product.taxName(false, reservation.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceAfterTax(reservation.unit_price, reservation.quantity, reservation.product_id, reservation.discount).toFixed(2)"
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
                        <x-forms.label x-bind:for="`reservation[${index}][discount]`">
                            Discount <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`reservation[${index}][discount]`"
                                    x-bind:name="`reservation[${index}][discount]`"
                                    x-model="reservation.discount"
                                    type="number"
                                    placeholder="Discount in Percentage"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`reservation.${index}.discount`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`reservation[${index}][description]`">
                                Additional Notes <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    x-bind:id="`reservation[${index}][description]`"
                                    x-bind:name="`reservation[${index}][description]`"
                                    x-model="reservation.description"
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
                                    x-text="$store.errors.getErrors(`reservation.${index}.description`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </template>

    @include('components.common.pricing', ['data' => 'reservations'])

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
            Alpine.data("reservationMasterDetailForm", ({
                reservation
            }) => ({
                reservations: [],

                async init() {
                    await Promise.all([Company.init(), Product.init({{ Js::from($products) }}).inventoryType().forSale(), MerchandiseBatch.initAvailable({{ Js::from($merchandiseBatches) }})]);

                    if (reservation) {
                        this.reservations = reservation;

                        await Promise.resolve(this.reservations.forEach((reservation) => reservation.product_category_id = Product.productCategoryId(reservation.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.reservations.push({});
                },
                async remove(index) {
                    if (this.reservations.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.reservations.splice(index, 1));

                    await Promise.resolve(
                        this.reservations.forEach((reservation, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), reservation.product_id, reservation.product_category_id);

                                if (Product.isBatchable(this.reservations[i].product_id) && Company.canSelectBatchNumberOnForms()) {
                                    MerchandiseBatch.appendMerchandiseBatches(
                                        this.getMerchandiseBatchesSelect(i),
                                        this.reservations[i].merchandise_batch_id,
                                        MerchandiseBatch.where(this.reservations[i].product_id, this.reservations[i].warehouse_id)
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
                        this.reservations[index].product_id = event.target.value;

                        haveData || (this.reservations[index].merchandise_batch_id = null);

                        this.reservations[index].product_category_id =
                            Product.productCategoryId(
                                this.reservations[index].product_id
                            );

                        if (Product.isBatchable(this.reservations[index].product_id) && Company.canSelectBatchNumberOnForms()) {
                            MerchandiseBatch.appendMerchandiseBatches(
                                this.getMerchandiseBatchesSelect(index),
                                this.reservations[index].merchandise_batch_id,
                                MerchandiseBatch.where(this.reservations[index].product_id, this.reservations[index].warehouse_id)
                            );
                        }

                        if (this.reservations[index].product_id && this.reservations[index].warehouse_id) {
                            batches = MerchandiseBatch.where(this.reservations[index].product_id, this.reservations[index].warehouse_id);
                        }

                        if (batches.length <= 1) {
                            this.reservations[index].merchandise_batch_id = batches[0]?.id;
                        }

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.reservations[index].product_id, this.reservations[index].product_category_id);

                            this.reservations[index].unit_price = Product.prices(
                                this.reservations[index].product_id
                            ).length ? Product.prices(
                                this.reservations[index].product_id
                            )[0].fixed_price : "";
                        }

                        this.getInventoryLevel(index);
                    });
                },
                getSelect2(index) {
                    return $(".product-list").eq(index);
                },
                getMerchandiseBatchesSelect(index) {
                    return document.getElementsByClassName("merchandise-batches")[index].firstElementChild;
                },
                async getInventoryLevel(index) {
                    if (!Company.isInventoryCheckerEnabled() || !this.reservations[index].product_id || !this.reservations[index].warehouse_id) {
                        return;
                    }

                    this.reservations[index].availableQuantity = null;

                    if (Product.isBatchable(this.reservations[index].product_id) && this.reservations[index].merchandise_batch_id) {
                        let merchandiseBatch = MerchandiseBatch.whereBatchId(this.reservations[index].merchandise_batch_id);
                        this.reservations[index].availableQuantity = merchandiseBatch?.quantity + " " + Product.whereProductId(this.reservations[index].product_id)?.unit_of_measurement;
                        return;
                    }

                    await Merchandise.init(this.reservations[index].product_id, this.reservations[index].warehouse_id);

                    this.reservations[index].availableQuantity = Merchandise.merchandise;
                },
                warehouseChanged(index) {
                    if (!Product.isBatchable(this.reservations[index].product_id) || Company.canSelectBatchNumberOnForms()) {
                        this.getInventoryLevel(index);
                    }

                    let batches = [];

                    this.reservations[index].merchandise_batch_id = null

                    MerchandiseBatch.appendMerchandiseBatches(
                        this.getMerchandiseBatchesSelect(index),
                        this.reservations[index].merchandise_batch_id,
                        MerchandiseBatch.where(this.reservations[index].product_id, this.reservations[index].warehouse_id),
                    );

                    if (this.reservations[index].product_id && this.reservations[index].warehouse_id) {
                        batches = MerchandiseBatch.where(this.reservations[index].product_id, this.reservations[index].warehouse_id);
                    }

                    if (batches.length <= 1) {
                        this.reservations[index].merchandise_batch_id = batches[0]?.id;
                    }
                }
            }));
        });
    </script>
@endpush

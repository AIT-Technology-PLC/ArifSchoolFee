<x-content.main
    x-data="sivMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('siv.*')) }})"
>
    <template
        x-for="(siv, index) in sivs"
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
                        x-bind:class="{ 'is-6': !Product.isBatchable(siv.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(siv.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                    >
                        <x-forms.label x-bind:for="`siv[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="siv.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), siv.product_id, siv.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`siv[${index}][product_id]`"
                                    x-bind:name="`siv[${index}][product_id]`"
                                    x-model="siv.product_id"
                                    x-init="select2(index)"
                                    inventory-type
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`siv.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (userCompany()->canSelectBatchNumberOnForms())
                        <div
                            class="column is-4"
                            x-show="Product.isBatchable(siv.product_id)"
                        >
                            <x-forms.label x-bind:for="`siv[${index}][merchandise_batch_id]`">
                                Batch No <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.select
                                        class="merchandise-batches is-fullwidth"
                                        x-bind:id="`siv[${index}][merchandise_batch_id]`"
                                        x-bind:name="`siv[${index}][merchandise_batch_id]`"
                                        x-model="siv.merchandise_batch_id"
                                        x-on:change="getInventoryLevel(index)"
                                    ></x-forms.select>
                                    <x-common.icon
                                        name="fas fa-th"
                                        class="is-small is-left"
                                    />
                                    <span
                                        class="help has-text-danger"
                                        x-text="$store.errors.getErrors(`siv.${index}.merchandise_batch_id`)"
                                    ></span>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(siv.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(siv.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`siv[${index}][warehouse_id]`">
                                From <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-init="$nextTick(() => { siv.warehouse_id = $el.value })"
                                    x-bind:id="`siv[${index}][warehouse_id]`"
                                    x-bind:name="`siv[${index}][warehouse_id]`"
                                    x-model="siv.warehouse_id"
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
                                    x-text="$store.errors.getErrors(`siv.${index}.warehouse_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`siv[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                            @if (userCompany()->isInventoryCheckerEnabled())
                                <sup
                                    class="tag bg-lightpurple text-purple"
                                    x-show="siv.availableQuantity"
                                    x-text="siv.availableQuantity"
                                    x-bind:class="{ 'bg-lightpurple text-purple': parseFloat(siv.availableQuantity) <= 0, 'bg-lightgreen text-green': parseFloat(siv.availableQuantity) > 0 }"
                                >
                                </sup>
                            @endif
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`siv[${index}][quantity]`"
                                    x-bind:name="`siv[${index}][quantity]`"
                                    x-model="siv.quantity"
                                    type="number"
                                    x-bind:placeholder="Product.unitOfMeasurement(siv.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`siv.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`siv[${index}][description]`">
                                Additional Notes <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    x-bind:id="`siv[${index}][description]`"
                                    x-bind:name="`siv[${index}][description]`"
                                    x-model="siv.description"
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
                                    x-text="$store.errors.getErrors(`siv.${index}.description`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </template>
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
            Alpine.data("sivMasterDetailForm", ({
                siv
            }) => ({
                sivs: [],

                async init() {
                    await Promise.all([Company.init(), Product.init({{ Js::from($products) }}).active().inventoryType(), MerchandiseBatch.initAvailable({{ Js::from($merchandiseBatches) }})]);

                    if (siv) {
                        this.sivs = siv;

                        await Promise.resolve(this.sivs.forEach((siv) => siv.product_category_id = Product.productCategoryId(siv.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.sivs.push({});
                },
                async remove(index) {
                    if (this.sivs.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.sivs.splice(index, 1));

                    await Promise.resolve(
                        this.sivs.forEach((siv, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), siv.product_id, siv.product_category_id);

                                if (Product.isBatchable(this.sivs[i].product_id) && Company.canSelectBatchNumberOnForms()) {
                                    MerchandiseBatch.appendMerchandiseBatches(
                                        this.getMerchandiseBatchesSelect(i),
                                        this.sivs[i].merchandise_batch_id,
                                        MerchandiseBatch.where(this.sivs[i].product_id, this.sivs[i].warehouse_id),
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
                        this.sivs[index].product_id = event.target.value;

                        haveData || (this.sivs[index].merchandise_batch_id = null);

                        this.sivs[index].product_category_id =
                            Product.productCategoryId(
                                this.sivs[index].product_id
                            );

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.sivs[index].product_id, this.sivs[index].product_category_id);
                        }

                        if (!Product.isBatchable(this.sivs[index].product_id) || !Company.canSelectBatchNumberOnForms()) {
                            this.getInventoryLevel(index);
                            return;
                        }

                        MerchandiseBatch.appendMerchandiseBatches(
                            this.getMerchandiseBatchesSelect(index),
                            this.sivs[index].merchandise_batch_id,
                            MerchandiseBatch.where(this.sivs[index].product_id, this.sivs[index].warehouse_id),
                        );

                        if (this.sivs[index].product_id && this.sivs[index].warehouse_id) {
                            batches = MerchandiseBatch.where(this.sivs[index].product_id, this.sivs[index].warehouse_id);
                        }

                        if (batches.length <= 1) {
                            this.sivs[index].merchandise_batch_id = batches[0]?.id;
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
                    if (!Company.isInventoryCheckerEnabled() || !this.sivs[index].product_id || !this.sivs[index].warehouse_id) {
                        return;
                    }

                    this.sivs[index].availableQuantity = null;

                    if (Product.isBatchable(this.sivs[index].product_id) && this.sivs[index].merchandise_batch_id) {
                        let merchandiseBatch = MerchandiseBatch.whereBatchId(this.sivs[index].merchandise_batch_id);
                        this.sivs[index].availableQuantity = merchandiseBatch?.quantity + " " + Product.whereProductId(this.sivs[index].product_id)?.unit_of_measurement;
                        return;
                    }

                    await Merchandise.init(this.sivs[index].product_id, this.sivs[index].warehouse_id);

                    this.sivs[index].availableQuantity = Merchandise.merchandise;
                },
                warehouseChanged(index) {
                    if (!Product.isBatchable(this.sivs[index].product_id) || !Company.canSelectBatchNumberOnForms()) {
                        this.getInventoryLevel(index);
                        return;
                    }

                    let batches = [];

                    this.sivs[index].merchandise_batch_id = null

                    MerchandiseBatch.appendMerchandiseBatches(
                        this.getMerchandiseBatchesSelect(index),
                        this.sivs[index].merchandise_batch_id,
                        MerchandiseBatch.where(this.sivs[index].product_id, this.sivs[index].warehouse_id),
                    );

                    if (this.sivs[index].product_id && this.sivs[index].warehouse_id) {
                        batches = MerchandiseBatch.where(this.sivs[index].product_id, this.sivs[index].warehouse_id);
                    }

                    if (batches.length <= 1) {
                        this.sivs[index].merchandise_batch_id = batches[0]?.id;
                    }

                    this.getInventoryLevel(index);
                }
            }));
        });
    </script>
@endpush

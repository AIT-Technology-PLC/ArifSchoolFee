<x-content.main
    x-data="adjustmentMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('adjustment.*')) }})"
>
    <template
        x-for="(adjustment, index) in adjustments"
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
                        x-bind:class="{ 'is-6': !Product.isBatchable(adjustment.product_id), 'is-4': Product.isBatchable(adjustment.product_id) }"
                    >
                        <x-forms.label x-bind:for="`adjustment[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="adjustment.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), adjustment.product_id, adjustment.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`adjustment[${index}][product_id]`"
                                    x-bind:name="`adjustment[${index}][product_id]`"
                                    x-model="adjustment.product_id"
                                    x-init="select2(index)"
                                    :type="['Raw Material', 'Finished Goods']"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`adjustment.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-4"
                        x-show="Product.isBatchable(adjustment.product_id)"
                    >
                        <x-forms.label x-bind:for="`adjustment[${index}][merchandise_batch_id]`">
                            Batch No <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    class="merchandise-batches is-fullwidth"
                                    x-bind:id="`adjustment[${index}][merchandise_batch_id]`"
                                    x-bind:name="`adjustment[${index}][merchandise_batch_id]`"
                                    x-model="adjustment.merchandise_batch_id"
                                ></x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`adjustment.${index}.merchandise_batch_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(adjustment.product_id), 'is-4': Product.isBatchable(adjustment.product_id) }"
                    >
                        <x-forms.label x-bind:for="`adjustment[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`adjustment[${index}][quantity]`"
                                    x-bind:name="`adjustment[${index}][quantity]`"
                                    x-model="adjustment.quantity"
                                    type="number"
                                    x-bind:placeholder="Product.unitOfMeasurement(adjustment.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`adjustment.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`adjustment[${index}][is_subtract]`">
                                Operation <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:id="`adjustment[${index}][is_subtract]`"
                                    x-bind:name="`adjustment[${index}][is_subtract]`"
                                    x-model="adjustment.is_subtract"
                                >
                                    <option value="0"> Add </option>
                                    <option value="1"> Subtract </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`adjustment.${index}.is_subtract`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`adjustment[${index}][warehouse_id]`">
                                Warehouse <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-init="$nextTick(() => { adjustment.warehouse_id = $el.value })"
                                    x-bind:id="`adjustment[${index}][warehouse_id]`"
                                    x-bind:name="`adjustment[${index}][warehouse_id]`"
                                    x-model="adjustment.warehouse_id"
                                    x-on:change="warehouseChanged(index)"
                                >
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-warehouse"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`adjustment.${index}.warehouse_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`adjustment[${index}][reason]`">
                                Reason <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    x-bind:id="`adjustment[${index}][reason]`"
                                    x-bind:name="`adjustment[${index}][reason]`"
                                    x-model="adjustment.reason"
                                    class="textarea pl-6"
                                    placeholder="Describe reason for adjusting this product level"
                                >
                                </x-forms.textarea>
                                <x-common.icon
                                    name="fas fa-edit"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`adjustment.${index}.reason`)"
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
            Alpine.data("adjustmentMasterDetailForm", ({
                adjustment
            }) => ({
                adjustments: [],

                async init() {
                    await Promise.all([Company.init(), Product.init({{ Js::from($products) }}).inventoryType(), MerchandiseBatch.init()]);

                    if (adjustment) {
                        this.adjustments = adjustment;

                        await Promise.resolve(this.adjustments.forEach((adjustment) => adjustment.product_category_id = Product.productCategoryId(adjustment.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.adjustments.push({});
                },
                async remove(index) {
                    if (this.adjustments.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.adjustments.splice(index, 1));

                    await Promise.resolve(
                        this.adjustments.forEach((adjustment, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), adjustment.product_id, adjustment.product_category_id);

                                if (Product.isBatchable(this.adjustments[i].product_id)) {
                                    MerchandiseBatch.appendMerchandiseBatches(
                                        this.getMerchandiseBatchesSelect(i),
                                        this.adjustments[i].merchandise_batch_id,
                                        MerchandiseBatch.where(this.adjustments[i].product_id, this.adjustments[i].warehouse_id)
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
                        this.adjustments[index].product_id = event.target.value;

                        this.adjustments[index].product_category_id =
                            Product.productCategoryId(
                                this.adjustments[index].product_id
                            );

                        if (Product.isBatchable(this.adjustments[index].product_id)) {
                            MerchandiseBatch.appendMerchandiseBatches(
                                this.getMerchandiseBatchesSelect(index),
                                this.adjustments[index].merchandise_batch_id,
                                MerchandiseBatch.where(this.adjustments[index].product_id, this.adjustments[index].warehouse_id)
                            );
                        }

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.adjustments[index].product_id, this.adjustments[index].product_category_id);
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
                        this.adjustments[index].merchandise_batch_id,
                        MerchandiseBatch.where(this.adjustments[index].product_id, this.adjustments[index].warehouse_id),
                    )
                }
            }));
        });
    </script>
@endpush

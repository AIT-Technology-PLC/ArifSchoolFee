<x-content.main
    x-data="costUpdateMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('costUpdate.*')) }})"
>
    <template
        x-for="(costUpdate, index) in costUpdates"
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
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`costUpdate[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="costUpdate.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), costUpdate.product_id, costUpdate.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`costUpdate[${index}][product_id]`"
                                    x-bind:name="`costUpdate[${index}][product_id]`"
                                    x-model="costUpdate.product_id"
                                    x-init="select2(index)"
                                    inventory-type
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`costUpdate.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`costUpdate[${index}][average_unit_cost]`">
                            Average Unit Cost <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`costUpdate[${index}][average_unit_cost]`"
                                    x-bind:name="`costUpdate[${index}][average_unit_cost]`"
                                    x-model="costUpdate.average_unit_cost"
                                    type="number"
                                    placeholder="Average Unit Cost"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`costUpdate.${index}.average_unit_cost`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    type="button"
                                    mode="button"
                                    class="bg-green has-text-white"
                                    x-text="Product.unitOfMeasurement(costUpdate.product_id, 'Per')"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`costUpdate[${index}][fifo_unit_cost]`">
                            Fifo Unit Cost <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`costUpdate[${index}][fifo_unit_cost]`"
                                    x-bind:name="`costUpdate[${index}][fifo_unit_cost]`"
                                    x-model="costUpdate.fifo_unit_cost"
                                    type="number"
                                    placeholder="Fifo Unit Cost"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`costUpdate.${index}.fifo_unit_cost`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    type="button"
                                    mode="button"
                                    class="bg-green has-text-white"
                                    x-text="Product.unitOfMeasurement(costUpdate.product_id, 'Per')"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`costUpdate[${index}][lifo_unit_cost]`">
                            Lifo Unit Cost <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`costUpdate[${index}][lifo_unit_cost]`"
                                    x-bind:name="`costUpdate[${index}][lifo_unit_cost]`"
                                    x-model="costUpdate.lifo_unit_cost"
                                    type="number"
                                    placeholder="Lifo Unit Cost"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`costUpdate.${index}.lifo_unit_cost`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    type="button"
                                    mode="button"
                                    class="bg-green has-text-white"
                                    x-text="Product.unitOfMeasurement(costUpdate.product_id, 'Per')"
                                />
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
            Alpine.data("costUpdateMasterDetailForm", ({
                costUpdate
            }) => ({
                costUpdates: [],

                async init() {
                    await Product.init({{ Js::from($products) }}).inventoryType();

                    if (costUpdate) {
                        this.costUpdates = costUpdate;

                        await Promise.resolve(this.costUpdates.forEach((costUpdate) => costUpdate.product_category_id = Product.productCategoryId(costUpdate.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.costUpdates.push({});
                },
                async remove(index) {
                    if (this.costUpdates.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.costUpdates.splice(index, 1));

                    await Promise.resolve(
                        this.costUpdates.forEach((costUpdate, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), costUpdate.product_id, costUpdate.product_category_id);
                            }
                        })
                    );

                    Pace.restart();
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event, haveData = false) => {
                        this.costUpdates[index].product_id = event.target.value;

                        this.costUpdates[index].product_category_id =
                            Product.productCategoryId(
                                this.costUpdates[index].product_id
                            );

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.costUpdates[index].product_id, this.costUpdates[index].product_category_id);
                        }
                    });
                },
                getSelect2(index) {
                    return $(".product-list").eq(index);
                }
            }));
        });
    </script>
@endpush

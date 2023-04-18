<x-content.main
    x-data="grnMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('grn.*')) }})"
>
    <template
        x-for="(grn, index) in grns"
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
                        <x-forms.label x-bind:for="`grn[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="grn.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), grn.product_id, grn.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`grn[${index}][product_id]`"
                                    x-bind:name="`grn[${index}][product_id]`"
                                    x-model="grn.product_id"
                                    x-init="select2(index)"
                                    :type="['Raw Material', 'Finished Goods']"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`grn.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`grn[${index}][warehouse_id]`">
                                To <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-init="$nextTick(() => { grn.warehouse_id = $el.value })"
                                    x-bind:id="`grn[${index}][warehouse_id]`"
                                    x-bind:name="`grn[${index}][warehouse_id]`"
                                    x-model="grn.warehouse_id"
                                >
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            {{ ($grnDetail['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                        >{{ $warehouse->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-warehouse"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`grn.${index}.warehouse_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`grn[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`grn[${index}][quantity]`"
                                    x-bind:name="`grn[${index}][quantity]`"
                                    x-model="grn.quantity"
                                    type="number"
                                    x-bind:placeholder="Product.unitOfMeasurement(grn.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`grn.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-show="(Product.whereProductId(grn.product_id)?.is_batchable ==1)"
                    >
                        <x-forms.label x-bind:for="`grn[${index}][batch_no]`">
                            Batch No <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`grn[${index}][batch_no]`"
                                    x-bind:name="`grn[${index}][batch_no]`"
                                    x-model="grn.batch_no"
                                    type="text"
                                    placeholder="Batch No"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`grn.${index}.batch_no`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    x-bind:id="`grn[${index}][expires_on]`"
                                    x-bind:name="`grn[${index}][expires_on]`"
                                    x-init="grn.expires_on ? grn.expires_on = new Date(grn.expires_on).toLocaleDateString('en-CA') : grn.expires_on"
                                    x-model="grn.expires_on"
                                    type="date"
                                    placeholder="Expiry Date"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`grn.${index}.expires_on`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`grn[${index}][unit_cost]`">
                            Unit Cost <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`grn[${index}][unit_cost]`"
                                    x-bind:name="`grn[${index}][unit_cost]`"
                                    x-model="grn.unit_cost"
                                    type="number"
                                    placeholder="Unit Cost"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`grn.${index}.unit_cost`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    type="button"
                                    mode="button"
                                    class="bg-green has-text-white"
                                    x-text="Product.unitOfMeasurement(grn.product_id, 'Per')"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`grn[${index}][description]`">
                                Additional Notes <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    x-bind:id="`grn[${index}][description]`"
                                    x-bind:name="`grn[${index}][description]`"
                                    x-model="grn.description"
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
                                    x-text="$store.errors.getErrors(`grn.${index}.description`)"
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
            Alpine.data("grnMasterDetailForm", ({
                grn
            }) => ({
                grns: [],

                async init() {
                    await Product.init({{ Js::from($products) }}).inventoryType();

                    if (grn) {
                        this.grns = grn;

                        await Promise.resolve(this.grns.forEach((grn) => grn.product_category_id = Product.productCategoryId(grn.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.grns.push({});
                },
                async remove(index) {
                    if (this.grns.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.grns.splice(index, 1));

                    await Promise.resolve(
                        this.grns.forEach((grn, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), grn.product_id, grn.product_category_id);
                            }
                        })
                    );

                    Pace.restart();
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event, haveData = false) => {
                        this.grns[index].product_id = event.target.value;

                        this.grns[index].product_category_id =
                            Product.productCategoryId(
                                this.grns[index].product_id
                            );

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.grns[index].product_id, this.grns[index].product_category_id);
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

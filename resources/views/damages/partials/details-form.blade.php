<x-content.main
    x-data="damageMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('damage.*')) }})"
>
    <template
        x-for="(damage, index) in damages"
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
                        x-bind:class="{ 'is-6': !Product.isBatchable(damage.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(damage.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                    >
                        <x-forms.label x-bind:for="`damage[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="damage.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), damage.product_id, damage.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`damage[${index}][product_id]`"
                                    x-bind:name="`damage[${index}][product_id]`"
                                    x-model="damage.product_id"
                                    x-init="select2(index)"
                                    inventory-type
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`damage.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (userCompany()->canSelectBatchNumberOnForms())
                        <div
                            class="column is-4"
                            x-show="Product.isBatchable(damage.product_id)"
                        >
                            <x-forms.label x-bind:for="`damage[${index}][merchandise_batch_id]`">
                                Batch No <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.select
                                        class="merchandise-batches is-fullwidth"
                                        x-bind:id="`damage[${index}][merchandise_batch_id]`"
                                        x-bind:name="`damage[${index}][merchandise_batch_id]`"
                                        x-model="damage.merchandise_batch_id"
                                    ></x-forms.select>
                                    <x-common.icon
                                        name="fas fa-th"
                                        class="is-small is-left"
                                    />
                                    <span
                                        class="help has-text-danger"
                                        x-text="$store.errors.getErrors(`damage.${index}.merchandise_batch_id`)"
                                    ></span>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(damage.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(damage.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`damage[${index}][warehouse_id]`">
                                From <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-init="$nextTick(() => { damage.warehouse_id = $el.value })"
                                    x-bind:id="`damage[${index}][warehouse_id]`"
                                    x-bind:name="`damage[${index}][warehouse_id]`"
                                    x-model="damage.warehouse_id"
                                    x-on:change="warehouseChanged(index)"
                                >
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            {{ ($damageDetail['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                        >{{ $warehouse->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-warehouse"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`damage.${index}.warehouse_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`damage[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`damage[${index}][quantity]`"
                                    x-bind:name="`damage[${index}][quantity]`"
                                    x-model="damage.quantity"
                                    type="number"
                                    x-bind:placeholder="Product.unitOfMeasurement(damage.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`damage.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`damage[${index}][description]`">
                                Additional Notes <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    x-bind:id="`damage[${index}][description]`"
                                    x-bind:name="`damage[${index}][description]`"
                                    x-model="damage.description"
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
                                    x-text="$store.errors.getErrors(`damage.${index}.description`)"
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
            Alpine.data("damageMasterDetailForm", ({
                damage
            }) => ({
                damages: [],

                async init() {
                    await Promise.all([Company.init(), Product.init({{ Js::from($products) }}).inventoryType(), MerchandiseBatch.initAvailable({{ Js::from($merchandiseBatches) }})]);

                    if (damage) {
                        this.damages = damage;

                        await Promise.resolve(this.damages.forEach((damage) => damage.product_category_id = Product.productCategoryId(damage.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.damages.push({});
                },
                async remove(index) {
                    if (this.damages.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.damages.splice(index, 1));

                    await Promise.resolve(
                        this.damages.forEach((damage, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), damage.product_id, damage.product_category_id);

                                if (Product.isBatchable(this.damages[i].product_id) && Company.canSelectBatchNumberOnForms()) {
                                    MerchandiseBatch.appendMerchandiseBatches(
                                        this.getMerchandiseBatchesSelect(i),
                                        this.damages[i].merchandise_batch_id,
                                        MerchandiseBatch.where(this.damages[i].product_id, this.damages[i].warehouse_id)
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
                        this.damages[index].product_id = event.target.value;

                        this.damages[index].product_category_id =
                            Product.productCategoryId(
                                this.damages[index].product_id
                            );

                        if (Product.isBatchable(this.damages[index].product_id) && Company.canSelectBatchNumberOnForms()) {
                            MerchandiseBatch.appendMerchandiseBatches(
                                this.getMerchandiseBatchesSelect(index),
                                this.damages[index].merchandise_batch_id,
                                MerchandiseBatch.where(this.damages[index].product_id, this.damages[index].warehouse_id)
                            );
                        }

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.damages[index].product_id, this.damages[index].product_category_id);
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
                        this.damages[index].merchandise_batch_id,
                        MerchandiseBatch.where(this.damages[index].product_id, this.damages[index].warehouse_id),
                    )
                }
            }));
        });
    </script>
@endpush

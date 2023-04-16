<x-content.main
    x-data="transferMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('transfer.*')) }})"
>
    <template
        x-for="(transfer, index) in transfers"
        x-bind:key="index"
    >
        <div
            class="mx-3"
            @transferred-from.window="warehouseChanged(index)"
        >
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
                    <input
                        type="hidden"
                        x-bind:name="`transfer[${index}][warehouse_id]`"
                        x-bind:value="transfer.warehouse_id = $store.transferredFrom"
                    >
                    <div
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(transfer.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(transfer.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                    >
                        <x-forms.label x-bind:for="`transfer[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="transfer.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), transfer.product_id, transfer.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`transfer[${index}][product_id]`"
                                    x-bind:name="`transfer[${index}][product_id]`"
                                    x-model="transfer.product_id"
                                    x-init="select2(index)"
                                    :type="['Raw Material', 'Finished Goods']"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`transfer.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (userCompany()->canSelectBatchNumberOnForms())
                        <div
                            class="column is-4"
                            x-show="Product.isBatchable(transfer.product_id)"
                        >
                            <x-forms.label x-bind:for="`transfer[${index}][merchandise_batch_id]`">
                                Batch No <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.select
                                        class="merchandise-batches is-fullwidth"
                                        x-bind:id="`transfer[${index}][merchandise_batch_id]`"
                                        x-bind:name="`transfer[${index}][merchandise_batch_id]`"
                                        x-model="transfer.merchandise_batch_id"
                                    ></x-forms.select>
                                    <x-common.icon
                                        name="fas fa-th"
                                        class="is-small is-left"
                                    />
                                    <span
                                        class="help has-text-danger"
                                        x-text="$store.errors.getErrors(`transfer.${index}.merchandise_batch_id`)"
                                    ></span>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div
                        class="column is-6"
                        x-bind:class="{ 'is-6': !Product.isBatchable(transfer.product_id) || !{{ userCompany()->canSelectBatchNumberOnForms() }}, 'is-4': Product.isBatchable(transfer.product_id) && {{ userCompany()->canSelectBatchNumberOnForms() }} }"
                    >
                        <x-forms.label x-bind:for="`transfer[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`transfer[${index}][quantity]`"
                                    x-bind:name="`transfer[${index}][quantity]`"
                                    x-model="transfer.quantity"
                                    type="number"
                                    x-bind:placeholder="Product.unitOfMeasurement(transfer.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`transfer.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`transfer[${index}][description]`">
                                Additional Notes <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    x-bind:id="`transfer[${index}][description]`"
                                    x-bind:name="`transfer[${index}][description]`"
                                    x-model="transfer.description"
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
                                    x-text="$store.errors.getErrors(`transfer.${index}.description`)"
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
            Alpine.store('transferredFrom', null);

            Alpine.data("transferMasterDetailForm", ({
                transfer
            }) => ({
                transfers: [],

                async init() {
                    await Promise.all([Company.init(), Product.initInventoryType({{ Js::from($products) }}), MerchandiseBatch.initAvailable()]);

                    if (transfer) {
                        this.transfers = transfer;

                        await Promise.resolve(this.transfers.forEach((transfer) => transfer.product_category_id = Product.productCategoryId(transfer.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.transfers.push({});
                },
                async remove(index) {
                    if (this.transfers.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.transfers.splice(index, 1));

                    await Promise.resolve(
                        this.transfers.forEach((transfer, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), transfer.product_id, transfer.product_category_id);

                                if (Product.isBatchable(this.transfers[i].product_id) && Company.canSelectBatchNumberOnForms()) {
                                    MerchandiseBatch.appendMerchandiseBatches(
                                        this.getMerchandiseBatchesSelect(i),
                                        this.transfers[i].merchandise_batch_id,
                                        MerchandiseBatch.where(this.transfers[i].product_id, Alpine.store('transferredFrom'))
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
                        this.transfers[index].product_id = event.target.value;

                        this.transfers[index].product_category_id =
                            Product.productCategoryId(
                                this.transfers[index].product_id
                            );

                        if (Product.isBatchable(this.transfers[index].product_id) && Company.canSelectBatchNumberOnForms()) {
                            MerchandiseBatch.appendMerchandiseBatches(
                                this.getMerchandiseBatchesSelect(index),
                                this.transfers[index].merchandise_batch_id,
                                MerchandiseBatch.where(this.transfers[index].product_id, Alpine.store('transferredFrom'))
                            );
                        }

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.transfers[index].product_id, this.transfers[index].product_category_id);
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
                        this.transfers[index].merchandise_batch_id,
                        MerchandiseBatch.where(this.transfers[index].product_id, Alpine.store('transferredFrom')),
                    )
                }
            }));
        });
    </script>
@endpush

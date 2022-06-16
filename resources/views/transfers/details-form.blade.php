<x-content.main
    x-data="transferMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('transfer.*')) }})"
>
    <template
        x-for="(transfer, index) in transfers"
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
                                    x-on:change="changeProductCategory(index)"
                                />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`transfer[${index}][product_id]`"
                                    x-bind:name="`transfer[${index}][product_id]`"
                                    x-model="transfer.product_id"
                                    x-init="select2(index)"
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
                    <div class="column is-6">
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
                                    placeholder="Quantity"
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
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    type="button"
                                    mode="button"
                                    class="bg-green has-text-white"
                                    x-text="$store.products.unitOfMeasurement(transfer.product_id)"
                                />
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
                                    </x-formtextarea>
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
            Alpine.data("transferMasterDetailForm", ({
                transfer
            }) => ({
                transfers: [],
                productStore: Alpine.reactive(Alpine.store("products")),

                init() {
                    if (transfer) {
                        this.transfers = transfer;

                        Alpine.effect(() => {
                            if (this.productStore.products.length) {
                                Promise.resolve(
                                    this.transfers.forEach((transfer) => {
                                        transfer.product_category_id =
                                            this.productStore.productCategoryId(
                                                transfer.product_id
                                            );
                                    })
                                ).then(() =>
                                    $(".product-list").trigger("change", [true])
                                );
                            }
                        });

                        return;
                    }

                    this.add();
                },
                add() {
                    this.transfers.push({
                        product_id: "",
                        product_category_id: "",
                        quantity: "",
                        description: "",
                    });
                },
                remove(index) {
                    if (this.transfers.length === 1) {
                        return;
                    }

                    Promise.resolve(this.transfers.splice(index, 1)).then(() =>
                        $(".product-list").trigger("change", [true])
                    );
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event, haveData = false) => {
                        this.transfers[index].product_id = event.target.value;

                        this.transfers[index].product_category_id =
                            this.productStore.productCategoryId(
                                this.transfers[index].product_id
                            );

                        if (!haveData) {
                            this.changeProductCategory(index);
                        }
                    });
                },
                changeProductCategory(index) {
                    let products = this.productStore.whereProductCategoryId(
                        this.transfers[index].product_category_id
                    );

                    this.productStore.appendProductsToSelect2(
                        $(".product-list").eq(index),
                        this.transfers[index].product_id,
                        products
                    );
                },
            }));
        });
    </script>
@endpush

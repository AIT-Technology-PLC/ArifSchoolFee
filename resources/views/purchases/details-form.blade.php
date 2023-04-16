<x-content.main
    x-data="purchaseMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('purchase.*')) }})"
>
    <template
        x-for="(purchase, index) in purchases"
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
                        <x-forms.label x-bind:for="`purchase[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="purchase.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), purchase.product_id, purchase.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`purchase[${index}][product_id]`"
                                    x-bind:name="`purchase[${index}][product_id]`"
                                    x-model="purchase.product_id"
                                    x-init="select2(index)"
                                    :type="['Raw Material', 'Finished Goods']"
                                    includedProducts="purchases"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`purchase.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`purchase[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`purchase[${index}][quantity]`"
                                    x-bind:name="`purchase[${index}][quantity]`"
                                    x-model="purchase.quantity"
                                    x-bind:placeholder="Product.unitOfMeasurement(purchase.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`purchase.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`purchase[${index}][unit_price]`">
                            Unit Price <span x-text="currency && `in ${currency}`"></span> <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`purchase[${index}][unit_price]`"
                                    x-bind:name="`purchase[${index}][unit_price]`"
                                    x-model="purchase.unit_price"
                                    placeholder="Purchase Price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`purchase.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    type="button"
                                    mode="button"
                                    class="bg-green has-text-white"
                                    x-text="Product.unitOfMeasurement(purchase.product_id, 'Per')"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.label x-bind:for="`purchase[${index}][amount]`">
                            Freight Volume <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`purchase[${index}][amount]`"
                                    x-bind:name="`purchase[${index}][amount]`"
                                    x-model="purchase.amount"
                                    placeholder="Freight Volume"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`purchase.${index}.amount`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    type="button"
                                    mode="button"
                                    class="bg-green has-text-white"
                                    x-text="freightUnit"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`purchase[${index}][duty_rate]`">
                                Duty Rate (%) <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`purchase[${index}][duty_rate]`"
                                    x-bind:name="`purchase[${index}][duty_rate]`"
                                    placeholder="Duty Rate in Percentage"
                                    x-model="purchase.duty_rate"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`purchase.${index}.duty_rate`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`purchase[${index}][excise_tax]`">
                                Excise Tax (%) <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`purchase[${index}][excise_tax]`"
                                    x-bind:name="`purchase[${index}][excise_tax]`"
                                    placeholder="Excise Tax in Percentage"
                                    x-model="purchase.excise_tax"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`purchase.${index}.excise_tax`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`purchase[${index}][vat_rate]`">
                                VAT Rate (%) <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`purchase[${index}][vat_rate]`"
                                    x-bind:name="`purchase[${index}][vat_rate]`"
                                    placeholder="VAT Rate in Percentage"
                                    x-model="purchase.vat_rate"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`purchase.${index}.vat_rate`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`purchase[${index}][surtax]`">
                                Surtax (%) <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`purchase[${index}][surtax]`"
                                    x-bind:name="`purchase[${index}][surtax]`"
                                    placeholder="Surtax in Percentage"
                                    x-model="purchase.surtax"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`purchase.${index}.surtax`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.field>
                            <x-forms.label x-bind:for="`purchase[${index}][withholding_tax]`">
                                Withholding Tax (%) <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`purchase[${index}][withholding_tax]`"
                                    x-bind:name="`purchase[${index}][withholding_tax]`"
                                    placeholder="Withholding Tax in Percentage"
                                    x-model="purchase.withholding_tax"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`purchase.${index}.withholding_tax`)"
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
            Alpine.data("purchaseMasterDetailForm", ({
                purchase
            }) => ({
                purchases: [],

                async init() {
                    await Product.init({{ Js::from($products) }}).forPurchase().inventoryType();

                    if (purchase) {
                        this.purchases = purchase;

                        await Promise.resolve(this.purchases.forEach((purchase) => purchase.product_category_id = Product.productCategoryId(purchase.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.purchases.push({});
                },
                async remove(index) {
                    if (this.purchases.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.purchases.splice(index, 1));

                    await Promise.resolve(
                        this.purchases.forEach((purchase, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), purchase.product_id, purchase.product_category_id);
                            }
                        })
                    );

                    Pace.restart();
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event, haveData = false) => {
                        this.purchases[index].product_id = event.target.value;

                        this.purchases[index].product_category_id =
                            Product.productCategoryId(
                                this.purchases[index].product_id
                            );

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.purchases[index].product_id, this.purchases[index].product_category_id);
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

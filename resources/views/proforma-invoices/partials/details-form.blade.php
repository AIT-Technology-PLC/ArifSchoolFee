<x-content.main
    x-data="proformaInvoiceMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('proformaInvoice.*')) }})"
>
    <template
        x-for="(proformaInvoice, index) in proformaInvoices"
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
                    <div class="column is-12">
                        <x-forms.label x-bind:for="`proformaInvoice[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 20%"
                            >
                                <x-common.category-list
                                    x-model="proformaInvoice.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), proformaInvoice.product_id, proformaInvoice.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`proformaInvoice[${index}][product_id]`"
                                    x-bind:name="`proformaInvoice[${index}][product_id]`"
                                    x-model="proformaInvoice.product_id"
                                    x-init="select2(index)"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`proformaInvoice.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`proformaInvoice[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`proformaInvoice[${index}][quantity]`"
                                    x-bind:name="`proformaInvoice[${index}][quantity]`"
                                    x-model="proformaInvoice.quantity"
                                    type="number"
                                    placeholder="Quantity"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`proformaInvoice.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    type="button"
                                    mode="button"
                                    class="bg-green has-text-white"
                                    x-text="Product.unitOfMeasurement(proformaInvoice.product_id)"
                                    tabindex="-1"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label x-bind:for="`proformaInvoice[${index}][unit_price]`">
                            Unit Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName({{ userCompany()->isPriceBeforeTax() }}, proformaInvoice.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`proformaInvoice[${index}][unit_price]`"
                                    x-bind:name="`proformaInvoice[${index}][unit_price]`"
                                    x-model="proformaInvoice.unit_price"
                                    x-bind:readonly="Product.isPriceFixed(proformaInvoice.product_id)"
                                    type="number"
                                    placeholder="Unit Price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`proformaInvoice.${index}.unit_price`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3">
                        <x-forms.label>
                            Total Price <sup
                                class="has-text-weight-light"
                                x-text="Product.taxName(true, proformaInvoice.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceBeforeTax(proformaInvoice.unit_price, proformaInvoice.quantity, proformaInvoice.discount).toFixed(2)"
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
                                x-text="Product.taxName(false, proformaInvoice.product_id)"
                            ></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:value="Product.priceAfterTax(proformaInvoice.unit_price, proformaInvoice.quantity, proformaInvoice.product_id, proformaInvoice.discount).toFixed(2)"
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
                        <x-forms.label x-bind:for="`proformaInvoice[${index}][discount]`">
                            Discount <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`proformaInvoice[${index}][discount]`"
                                    x-bind:name="`proformaInvoice[${index}][discount]`"
                                    x-model="proformaInvoice.discount"
                                    type="number"
                                    placeholder="Discount in Percentage"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`proformaInvoice.${index}.discount`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`proformaInvoice[${index}][specification]`">
                                Specifications <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.textarea
                                    rows="5"
                                    class="summernote-details"
                                    placeholder="Specification about the product"
                                    x-bind:id="`proformaInvoice[${index}][specification]`"
                                    x-bind:name="`proformaInvoice[${index}][specification]`"
                                    x-init="summernote(index)"
                                    x-model="proformaInvoice.specification"
                                ></x-forms.textarea>
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`proformaInvoice.${index}.specification`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </template>

    @include('components.content.pricing', ['data' => 'proformaInvoices'])

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
            Alpine.data("proformaInvoiceMasterDetailForm", ({
                proformaInvoice
            }) => ({
                proformaInvoices: [],

                async init() {
                    await Product.init();

                    if (proformaInvoice) {
                        this.proformaInvoices = proformaInvoice;

                        await Promise.resolve(this.proformaInvoices.forEach((proformaInvoice) => proformaInvoice.product_category_id = Product.productCategoryId(proformaInvoice.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.proformaInvoices.push({});
                },
                async remove(index) {
                    if (this.proformaInvoices.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.proformaInvoices.splice(index, 1));

                    await Promise.resolve(
                        this.proformaInvoices.forEach((proformaInvoice, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), proformaInvoice.product_id, proformaInvoice.product_category_id);
                                $(".summernote-details").eq(i).summernote("code", proformaInvoice.specification);
                            }
                        })
                    );

                    Pace.restart();
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event, haveData = false) => {
                        this.proformaInvoices[index].product_id = event.target.value;

                        this.proformaInvoices[index].product_category_id =
                            Product.productCategoryId(
                                this.proformaInvoices[index].product_id
                            );

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.proformaInvoices[index].product_id, this.proformaInvoices[index].product_category_id);

                            this.proformaInvoices[index].unit_price = Product.price(
                                this.proformaInvoices[index].product_id
                            );
                        }
                    });
                },
                summernote(index) {
                    let object = this;

                    let summernote = $(this.$el).summernote({
                        placeholder: "Write description or other notes here",
                        tabsize: 2,
                        minHeight: 90,
                        tabDisable: true,
                        toolbar: [
                            ["font", ["bold"]],
                            ["table", ["table"]],
                            ["forecolor", ["forecolor"]],
                        ],
                        callbacks: {
                            onInit: function() {
                                $(this).summernote("code", object.proformaInvoices[index].specification);
                            },
                            onChange: function(contents, $editable) {
                                object.proformaInvoices[index].specification = contents;
                            }
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

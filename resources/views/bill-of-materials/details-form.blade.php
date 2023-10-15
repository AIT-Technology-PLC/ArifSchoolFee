<x-content.main
    x-data="billOfMaterialMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('billOfMaterial.*')) }})"
>
    <template
        x-for="(billOfMaterial, index) in billOfMaterials"
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
                        <x-forms.label x-bind:for="`billOfMaterial[${index}][product_id]`">
                            Product <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="billOfMaterial.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), billOfMaterial.product_id, billOfMaterial.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    class="product-list"
                                    x-bind:id="`billOfMaterial[${index}][product_id]`"
                                    x-bind:name="`billOfMaterial[${index}][product_id]`"
                                    x-model="billOfMaterial.product_id"
                                    x-init="select2(index)"
                                    raw-material
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`billOfMaterial.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`billOfMaterial[${index}][quantity]`">
                            Quantity <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    x-bind:id="`billOfMaterial[${index}][quantity]`"
                                    x-bind:name="`billOfMaterial[${index}][quantity]`"
                                    x-model="billOfMaterial.quantity"
                                    x-bind:placeholder="Product.unitOfMeasurement(billOfMaterial.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`billOfMaterial.${index}.quantity`)"
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
            Alpine.data("billOfMaterialMasterDetailForm", ({
                billOfMaterial
            }) => ({
                billOfMaterials: [],

                async init() {
                    await Product.init({{ Js::from($products) }}).rawMaterial();

                    if (billOfMaterial) {
                        this.billOfMaterials = billOfMaterial;

                        await Promise.resolve(this.billOfMaterials.forEach((billOfMaterial) => billOfMaterial.product_category_id = Product.productCategoryId(billOfMaterial.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.billOfMaterials.push({});
                },
                async remove(index) {
                    if (this.billOfMaterials.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.billOfMaterials.splice(index, 1));

                    await Promise.resolve(
                        this.billOfMaterials.forEach((billOfMaterial, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), billOfMaterial.product_id, billOfMaterial.product_category_id);
                            }
                        })
                    );

                    Pace.restart();
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event, haveData = false) => {
                        this.billOfMaterials[index].product_id = event.target.value;

                        this.billOfMaterials[index].product_category_id =
                            Product.productCategoryId(
                                this.billOfMaterials[index].product_id
                            );

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.billOfMaterials[index].product_id, this.billOfMaterials[index].product_category_id);
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

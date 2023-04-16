<x-content.main
    x-data="jobMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('job.*')) }})"
>
    <template
        x-for="(job, index) in jobs"
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
                        <x-forms.label x-bind:for="`job[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="job.product_category_id"
                                    x-on:change="Product.changeProductCategory(getSelect2(index), job.product_id, job.product_category_id)"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    :type="['Finished Goods']"
                                    class="product-list"
                                    x-bind:id="`job[${index}][product_id]`"
                                    x-bind:name="`job[${index}][product_id]`"
                                    x-model="job.product_id"
                                    x-init="select2(index)"
                                    included-products="jobs"
                                    :type="['Finished Goods']"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`job.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`job[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`job[${index}][quantity]`"
                                    x-bind:name="`job[${index}][quantity]`"
                                    x-model="job.quantity"
                                    type="number"
                                    x-bind:placeholder="Product.unitOfMeasurement(job.product_id) || ''"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`job.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`job[${index}][bill_of_material_id]`">
                            Bill Of Material <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    class="bill-of-materials is-fullwidth"
                                    x-bind:id="`job[${index}][bill_of_material_id]`"
                                    x-bind:name="`job[${index}][bill_of_material_id]`"
                                    x-model="job.bill_of_material_id"
                                >
                                    <option value="">Select Bill of Material</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`job.${index}.bill_of_material_id`)"
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
            Alpine.data("jobMasterDetailForm", ({
                job
            }) => ({
                jobs: [],

                async init() {
                    await Promise.all([Product.init({{ Js::from($products) }}).finishedGoods().forJob(), BillOfMaterial.init()]);

                    if (job) {
                        this.jobs = job;

                        await Promise.resolve(this.jobs.forEach((job) => job.product_category_id = Product.productCategoryId(job.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.jobs.push({});
                },
                async remove(index) {
                    if (this.jobs.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.jobs.splice(index, 1));

                    await Promise.resolve(
                        this.jobs.forEach((job, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), job.product_id, job.product_category_id);

                                BillOfMaterial.appendBillOfMaterials(
                                    this.getBillOfMaterialsSelect(i),
                                    this.jobs[i].bill_of_material_id,
                                    BillOfMaterial.whereProductId(this.jobs[i].product_id)
                                );
                            }
                        })
                    );

                    Pace.restart();
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event, haveData = false) => {
                        this.jobs[index].product_id = event.target.value;

                        this.jobs[index].product_category_id =
                            Product.productCategoryId(
                                this.jobs[index].product_id
                            );

                        BillOfMaterial.appendBillOfMaterials(
                            this.getBillOfMaterialsSelect(index),
                            this.jobs[index].bill_of_material_id,
                            BillOfMaterial.whereProductId(this.jobs[index].product_id)
                        );

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.jobs[index].product_id, this.jobs[index].product_category_id);
                        }
                    });
                },
                getSelect2(index) {
                    return $(".product-list").eq(index);
                },
                getBillOfMaterialsSelect(index) {
                    return document.getElementsByClassName("bill-of-materials")[index].firstElementChild;
                }
            }));
        });
    </script>
@endpush

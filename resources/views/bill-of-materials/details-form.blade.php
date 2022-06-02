<x-content.main
    x-data="billOfMaterialMasterDetailForm({{ Js::from($data) }})"
    x-init="setErrors({{ json_encode($errors->get('billOfMaterial.*')) }})"
>
    <x-common.fail-message :message="session('failedMessage')" />
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
                        <x-forms.field>
                            <x-forms.label x-bind:for="`billOfMaterial[${index}][product_id]`">
                                Product <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-common.product-list
                                    x-bind:id="`billOfMaterial[${index}][product_id]`"
                                    x-bind:name="`billOfMaterial[${index}][product_id]`"
                                    x-model="billOfMaterial.product_id"
                                    x-init="select2(index)"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="getErrors(`billOfMaterial.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-data="productDataProvider(billOfMaterial.product_id)"
                        x-init="getProduct(billOfMaterial.product_id) && $watch(`billOfMaterial.product_id`, (value) => getProduct(value))"
                    >
                        <x-forms.label x-bind:for="`billOfMaterial[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`billOfMaterial[${index}][quantity]`"
                                    x-bind:name="`billOfMaterial[${index}][quantity]`"
                                    x-model="billOfMaterial.quantity"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-large is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="getErrors(`billOfMaterial.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    type="button"
                                    mode="button"
                                    class="bg-green has-text-white"
                                    x-text="product.unit_of_measurement"
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

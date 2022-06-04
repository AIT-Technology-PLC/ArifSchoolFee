<x-content.main
    x-data="sivMasterDetailForm({{ Js::from($data) }})"
    x-init="setErrors({{ json_encode($errors->get('siv.*')) }})"
>
    <x-common.fail-message :message="session('failedMessage')" />
    <template
        x-for="(siv, index) in sivs"
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
                <div
                    name="sivFormGroup"
                    class="columns is-marginless is-multiline"
                    x-data="productDataProvider(siv.product_id)"
                    x-init="getProduct(siv.product_id) && $watch(`siv.product_id`, (value) => getProduct(value))"
                >
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`siv[${index}][product_id]`">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list
                                    x-model="selectedCategory"
                                    x-on:change="getProductsByCategory"
                                />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-common.product-list
                                    x-bind:id="`siv[${index}][product_id]`"
                                    x-bind:name="`siv[${index}][product_id]`"
                                    x-model="siv.product_id"
                                    x-init="select2(index)"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="getErrors(`siv.${index}.product_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`siv[${index}][warehouse_id]`">
                                From <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:id="`siv[${index}][warehouse_id]`"
                                    x-bind:name="`siv[${index}][warehouse_id]`"
                                    x-model="siv.warehouse_id"
                                >
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            {{ ($reservationDetail['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                        >{{ $warehouse->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-warehouse"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="getErrors(`siv.${index}.warehouse_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`siv[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`siv[${index}][quantity]`"
                                    x-bind:name="`siv[${index}][quantity]`"
                                    x-model="siv.quantity"
                                    type="number"
                                    placeholder="Quantity"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="getErrors(`siv.${index}.quantity`)"
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
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`siv[${index}][description]`">
                                Additional Notes <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    x-bind:id="`siv[${index}][description]`"
                                    x-bind:name="`siv[${index}][description]`"
                                    x-model="siv.description"
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
                                        x-text="getErrors(`siv.${index}.description`)"
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

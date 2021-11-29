@extends('layouts.app')

@section('title', 'Create Price')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Price" />
        <form
            id="formOne"
            action="{{ route('prices.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="priceMasterDetailForm({{ json_encode(session()->getOldInput()) }})"
                x-init="setErrors({{ json_encode($errors->get('price.*')) }})"
            >
                <template
                    x-for="(price, index) in prices"
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
                                        <x-forms.label x-bind:for="`price[${index}][product_id]`">
                                            Product <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-common.product-list
                                                :excluded-products="$excludedProducts"
                                                tags="false"
                                                selected-product-id=""
                                                name=""
                                                id=""
                                                x-bind:id="`price[${index}][product_id]`"
                                                x-bind:name="`price[${index}][product_id]`"
                                                x-init="select2(index)"
                                                x-model="price.product_id"
                                            />
                                            <x-common.icon
                                                name="fas fa-th"
                                                class="is-large is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="getErrors(`price.${index}.product_id`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`price[${index}][type]`">
                                            Type <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                id=""
                                                name=""
                                                x-bind:id="`price[${index}][type]`"
                                                x-bind:name="`price[${index}][type]`"
                                                x-model="price.type"
                                                x-on:change="changePriceType(price)"
                                            >
                                                <option
                                                    disabled
                                                    selected
                                                > Select Type </option>
                                                <option value="fixed"> Fixed </option>
                                                <option value="range"> Range </option>
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-tags"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="getErrors(`price.${index}.type`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div
                                    class="column is-6"
                                    x-bind:class="{ 'is-hidden': !isPriceFixed(price.type) }"
                                >
                                    <x-forms.label x-bind:for="`price[${index}][fixed_price]`">
                                        Fixed Price
                                        <sup class="has-text-danger">*</sup>
                                        <sup class="has-text-weight-light"> ({{ userCompany()->getPriceMethod() }})</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                x-bind:id="`price[${index}][fixed_price]`"
                                                x-bind:name="`price[${index}][fixed_price]`"
                                                type="number"
                                                placeholder="Fixed price"
                                                x-model="price.fixed_price"
                                            />
                                            <x-common.icon
                                                name="fas fa-money-bill"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="getErrors(`price.${index}.fixed_price`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div
                                    class="column is-6"
                                    x-bind:class="{ 'is-hidden': isPriceFixed(price.type) }"
                                >
                                    <x-forms.label x-bind:for="`price[${index}][min_price]`">
                                        Min. Price
                                        <sup class="has-text-danger">*</sup>
                                        <sup class="has-text-weight-light"> ({{ userCompany()->getPriceMethod() }})</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                x-bind:id="`price[${index}][min_price]`"
                                                x-bind:name="`price[${index}][min_price]`"
                                                type="number"
                                                placeholder="Min. price"
                                                x-model="price.min_price"
                                            />
                                            <x-common.icon
                                                name="fas fa-money-bill"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="getErrors(`price.${index}.min_price`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div
                                    class="column is-6"
                                    x-bind:class="{ 'is-hidden': isPriceFixed(price.type) }"
                                >
                                    <x-forms.label x-bind:for="`price[${index}][max_price]`">
                                        Max. Price
                                        <sup class="has-text-danger">*</sup>
                                        <sup class="has-text-weight-light"> ({{ userCompany()->getPriceMethod() }})</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                x-bind:id="`price[${index}][max_price]`"
                                                x-bind:name="`price[${index}][max_price]`"
                                                type="number"
                                                placeholder="Max. price"
                                                x-model="price.max_price"
                                            />
                                            <x-common.icon
                                                name="fas fa-money-bill"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="getErrors(`price.${index}.max_price`)"
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
                    id="addNewPriceForm"
                    label="Add More Item"
                    class="bg-purple has-text-white is-small ml-3 mt-6"
                    x-on:click="add"
                />
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

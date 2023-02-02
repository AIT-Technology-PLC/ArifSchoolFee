@extends('layouts.app')

@section('title', 'Edit Price Increment')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Price Increment" />
        <form
            id="formOne"
            action="{{ route('price-increments.update', $priceIncrement->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main x-data="targetProducts('{{ $priceIncrement->target_product }}')">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Reference Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    :readonly="!userCompany()->isEditingReferenceNumberEnabled()"
                                    value="{{ $priceIncrement->code }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label for="price_type">
                            Price Type <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control>
                                <x-forms.select name="price_type">
                                    <option
                                        selected
                                        disabled
                                        value=""
                                    >Price Type</option>
                                    <option
                                        value="amount"
                                        @selected(old('price_type', $priceIncrement->price_type) == 'amount')
                                    >Amount</option>
                                    <option
                                        value="percent"
                                        @selected(old('price_type', $priceIncrement->price_type) == 'percent')
                                    >Percent</option>
                                </x-forms.select>
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    name="price_increment"
                                    id="price_increment"
                                    value="{{ old('price_increment', $priceIncrement->price_increment) }}"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="price_increment" />
                                <x-common.validation-error property="price_type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column">
                        <x-forms.field>
                            <x-forms.label for="target_product">
                                Target Product <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="target_product"
                                    name="target_product"
                                    x-model="targetProduct"
                                    x-on:change="changeTargetProduct"
                                >
                                    <option
                                        selected
                                        disabled
                                        value=""
                                    >Select Target Product</option>
                                    <option value="All Products">All Products</option>
                                    <option value="Specific Products">Specific Products</option>
                                    <option value="Upload Excel">Upload Excel</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                            <x-common.validation-error property="target_product" />
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-invisible"
                        x-bind:class="{ 'is-invisible': isNotSpecificProduct }"
                    >
                        <x-forms.field>
                            <x-forms.label for="product_id">
                                Products <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    x-init="initializeSelect2($el, '')"
                                    class="is-fullwidth is-multiple"
                                    id="product_id"
                                    name="product_id[][product_id]"
                                    multiple="multiple"
                                >
                                    @foreach ($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            @selected(collect(old('product_id', $priceIncrement->priceIncrementDetails->toArray()))->where('product_id', $product->id)->isNotEmpty())
                                        >
                                            {{ $product->code ? str($product->name)->title()->singular()->append(' (', $product->code, ')') : str($product->name)->title()->singular() }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.validation-error property="product_id.*.product_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

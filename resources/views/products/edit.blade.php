@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Product" />
        <form
            id="formOne"
            action="{{ route('products.update', $product->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main x-data="productType(
                '{{ $product->type }}',
                '{{ $product->is_batchable }}',
                '{{ $product->batch_priority }}',
                '{{ $product->is_active }}',
                '{{ $product->is_product_single }}',
                '{{ old('unit_of_measurement_source', 'Custom') }}',
                '{{ old('unit_of_measurement', $product->unit_of_measurement) }}',
            )">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="type">
                                Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="type"
                                    name="type"
                                    x-model="type"
                                    x-on:change="changeProductType"
                                >
                                    <x-common.inventory-type-options :type="$product->type" />
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sitemap"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_product_single">
                                Product or Bundle <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="is_product_single"
                                    name="is_product_single"
                                    x-model="isProductSingle"
                                >
                                    <option value="1"> Product </option>
                                    <option value="0"> Bundle </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="is_product_single" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="name">
                                Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="Product/Service Name"
                                    value="{{ $product->name ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-boxes"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Code <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="code"
                                    name="code"
                                    type="text"
                                    placeholder="Product/Service Code"
                                    value="{{ $product->code ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="product_category_id">
                                Category <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="product_category_id"
                                    name="product_category_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Category</option>
                                    @foreach ($categories as $category)
                                        <option
                                            value="{{ $category->id }}"
                                            {{ $product->product_category_id == $category->id ? 'selected' : '' }}
                                        >
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-layer-group"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="product_category_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label for="unit_of_measurement">
                            Unit of Measurement <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="unit_of_measurement_source"
                                    name="unit_of_measurement_source"
                                    x-model="unitOfMeasurementSource"
                                >
                                    <option value="Standard">Standard</option>
                                    <option value="Custom">Custom</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                            <x-forms.control
                                class="is-expanded"
                                x-show="!isUnitOfMeasurementCustom()"
                            >
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="unit_of_measurement"
                                    name="unit_of_measurement"
                                    x-model="unitOfMeasurement"
                                >
                                    <x-common.measurement-unit-options />
                                </x-forms.select>
                                <x-common.validation-error property="unit_of_measurement" />
                            </x-forms.control>
                            <x-forms.control
                                class="is-expanded"
                                x-show="isUnitOfMeasurementCustom()"
                                x-cloak
                            >
                                <x-forms.input
                                    type="text"
                                    id="unit_of_measurement"
                                    name="unit_of_measurement"
                                    x-model="unitOfMeasurement"
                                />
                                <x-common.validation-error property="unit_of_measurement" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': isTypeService || isNonInventoryProduct || !isSingle() }"
                    >
                        <x-forms.field>
                            <x-forms.label for="min_on_hand">
                                Minimum Level <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="min_on_hand"
                                    name="min_on_hand"
                                    type="number"
                                    placeholder="What is considered low stock for this product?"
                                    value="{{ $product->min_on_hand ?? '0.00' }}"
                                />
                                <x-common.icon
                                    name="fas fa-battery-quarter"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="min_on_hand" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                    >
                        <x-forms.field>
                            <x-forms.label for="tax_id">
                                Tax Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="tax_id"
                                    name="tax_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Tax Type</option>
                                    @foreach ($taxes as $tax)
                                        <option
                                            value="{{ $tax->id }}"
                                            @selected($product->tax_id == $tax->id)
                                        >
                                            {{ $tax->type }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fa fa-file-invoice-dollar"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="tax_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (isFeatureEnabled('Supplier Management'))
                        <div
                            class="column is-6"
                            x-bind:class="{ 'is-hidden': isTypeService || isNonInventoryProduct }"
                        >
                            <x-forms.field>
                                <x-forms.label for="supplier_id">
                                    Supplier <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left select is-fullwidth">
                                    <x-common.supplier-list :selected-id="old('supplier_id', $product->supplier_id)" />
                                    <x-common.icon
                                        name="fas fa-address-card"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="supplier_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="!isTypeService && !isNonInventoryProduct && isSingle"
                    >
                        <x-forms.field>
                            <x-forms.label for="is_batchable">
                                Is Batchable <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="is_batchable"
                                    name="is_batchable"
                                    x-model="isBatchable"
                                    x-on:change="changeProductType"
                                >
                                    <option value="1">Yes</option>
                                    <option value="0"> No </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="is_batchable" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isBatchable == 1 && isSingle"
                    >
                        <x-forms.field>
                            <x-forms.label for="batch_priority">
                                Batch Priority <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="batch_priority"
                                    name="batch_priority"
                                    x-model="batchPriority"
                                    x-on:change="changeProductType"
                                >
                                    <option disabled>
                                        Select Batch Priority
                                    </option>
                                    <option value="fifo"> First Expire First Out </option>
                                    <option value="lifo"> Last Expire First Out </option>
                                    <option value=""> None</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="batch_priority" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isSingle()"
                    >
                        <x-forms.field>
                            <x-forms.label for="brand_id">
                                Brand <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="brand_id"
                                    name="brand_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Brands</option>
                                    @foreach ($brands as $brand)
                                        <option
                                            value="{{ $brand->id }}"
                                            {{ $product->brand_id == $brand->id ? 'selected' : '' }}
                                        >
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                    <option value="">None</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-trademark"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="brand_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_active">
                                Is Active <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="is_active"
                                    name="is_active"
                                    x-model="isActive"
                                >
                                    <option value="1"> Active </option>
                                    <option value="0"> Inactive </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="is_active" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': isActive == 0 }"
                    >
                        <x-forms.field>
                            <x-forms.label for="is_active_for_sale">
                                Is Active For Sales <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="is_active_for_sale"
                                    name="is_active_for_sale"
                                >
                                    <option
                                        value="1"
                                        @selected($product->isActiveForSale())
                                    > Active </option>
                                    <option
                                        value="0"
                                        @selected(!$product->isActiveForSale())
                                    > Inactive </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="is_active_for_sale" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': isActive == 0 }"
                    >
                        <x-forms.field>
                            <x-forms.label for="is_active_for_purchase">
                                Is Active For Purchase<sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="is_active_for_purchase"
                                    name="is_active_for_purchase"
                                >
                                    <option
                                        value="1"
                                        @selected($product->isActiveForPurchase())
                                    > Active </option>
                                    <option
                                        value="0"
                                        @selected(!$product->isActiveForPurchase())
                                    > Inactive </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="is_active_for_purchase" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': isActive == 0 }"
                    >
                        <x-forms.field>
                            <x-forms.label for="is_active_for_job">
                                Is Active For Job <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="is_active_for_job"
                                    name="is_active_for_job"
                                >
                                    <option
                                        value="1"
                                        @selected($product->isActiveForJob())
                                    > Active </option>
                                    <option
                                        value="0"
                                        @selected(!$product->isActiveForJob())
                                    > Inactive </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="is_active_for_job" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': isTypeService || isNonInventoryProduct }"
                    >
                        <x-forms.field>
                            <x-forms.label for="inventory_valuation_method">
                                Inventory Valuation Method <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="inventory_valuation_method"
                                    name="inventory_valuation_method"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Method
                                    </option>
                                    <option
                                        value="fifo"
                                        @selected($product->inventory_valuation_method == 'fifo')
                                    >
                                        FIFO
                                    </option>
                                    <option
                                        value="lifo"
                                        @selected($product->inventory_valuation_method == 'lifo')
                                    >
                                        LIFO
                                    </option>
                                    <option
                                        value="average"
                                        @selected($product->inventory_valuation_method == 'average')
                                    >
                                        Average
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="inventory_valuation_method" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="textarea summernote"
                                    placeholder="Description or note about the new category"
                                >{{ $product->description ?? '' }}</x-forms.textarea>
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
                <section
                    class="mt-5"
                    x-show="isSingle"
                >
                    <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3">
                        <h1 class="text-green is-size-5">
                            Reorder Levels
                        </h1>
                    </div>
                    <div class="box is-radiusless">
                        <div class="columns is-marginless is-multiline">
                            @foreach ($warehouses as $warehouse)
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label for="name">
                                            {{ $warehouse->name }} <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                id="reorder_level[{{ $warehouse->id }}]"
                                                name="reorder_level[{{ $warehouse->id }}]"
                                                type="text"
                                                value="{{ $product->productReorders->firstWhere('warehouse_id', $warehouse->id)->quantity ?? (old('reorder_level')[$warehouse->id] ?? '') }}"
                                            />
                                            <x-common.icon
                                                name="fas fa-boxes"
                                                class="is-small is-left"
                                            />
                                            <x-common.validation-error property="reorder_level.{{ $warehouse->id }}" />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                <section
                    class="mt-5"
                    x-cloak
                    x-show="!isSingle()"
                >
                    <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3">
                        <h1 class="text-green is-size-5">
                            Bundle Details
                        </h1>
                    </div>
                    @include('products.partials.details-form', [
                        'data' => ['productBundle' => old('productBundle') ?? $product->productBundles],
                    ])
                </section>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

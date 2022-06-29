@extends('layouts.app')

@section('title', 'Edit Return')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Return" />
        <form
            id="formOne"
            action="{{ route('returns.update', $return->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Return Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $return->code }}"
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
                        <x-forms.field>
                            <x-forms.label for="customer_id">
                                Customer <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="select is-fullwidth has-icons-left">
                                <x-common.customer-list
                                    id="customer_id"
                                    name="customer_id"
                                    key=""
                                    selected-id="{{ $return->customer_id }}"
                                />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="customer_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Issued On <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="datetime-local"
                                    name="issued_on"
                                    id="issued_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ $return->issued_on->toDateTimeLocalString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="issued_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="customer_id">
                                Description <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="pl-6"
                                    placeholder="Description or note to be taken"
                                >
                                    {{ $return->description }}
                                </x-forms.textarea>
                                <x-common.icon
                                    name="fas fa-edit"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
                @foreach ($return->returnDetails as $returnDetail)
                    <div class="has-text-weight-medium has-text-left mt-5">
                        <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                            Item {{ $loop->index + 1 }}
                        </span>
                    </div>
                    <div
                        x-data="productDataProvider({{ $returnDetail->product_id }})"
                        class="box has-background-white-bis radius-top-0"
                    >
                        <div
                            name="returnFormGroup"
                            class="columns is-marginless is-multiline"
                        >
                            <div class="column is-6">
                                <label
                                    for="return[{{ $loop->index }}][product_id]"
                                    class="label text-green has-text-weight-normal"
                                >
                                    Product <sup class="has-text-danger">*</sup>
                                </label>
                                <div class="field has-addons">
                                    <div
                                        class="control has-icons-left"
                                        style="width: 30%"
                                    >
                                        <x-common.category-list
                                            x-model="selectedCategory"
                                            x-on:change="getProductsByCategory"
                                        />
                                    </div>
                                    <div class="control has-icons-left is-expanded">
                                        <x-common.product-list
                                            tags="false"
                                            name="return[{{ $loop->index }}]"
                                            selected-product-id="{{ $returnDetail->product_id }}"
                                            x-init="select2"
                                        />
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-th"></i>
                                        </div>
                                        @error('return.0.product_id')
                                            <span
                                                class="help has-text-danger"
                                                role="alert"
                                            >
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label
                                        for="return[{{ $loop->index }}][warehouse_id]"
                                        class="label text-green has-text-weight-normal"
                                    > To <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <div class="select is-fullwidth">
                                            <select
                                                id="return[{{ $loop->index }}][warehouse_id]"
                                                name="return[{{ $loop->index }}][warehouse_id]"
                                            >
                                                @foreach ($warehouses as $warehouse)
                                                    <option
                                                        value="{{ $warehouse->id }}"
                                                        {{ $returnDetail->warehouse_id == $warehouse->id ? 'selected' : '' }}
                                                    >{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-warehouse"></i>
                                        </div>
                                        @error('return.0.warehouse_id')
                                            <span
                                                class="help has-text-danger"
                                                role="alert"
                                            >
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label
                                    for="return[{{ $loop->index }}][quantity]"
                                    class="label text-green has-text-weight-normal"
                                >Quantity <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input
                                            id="return[{{ $loop->index }}][quantity]"
                                            name="return[{{ $loop->index }}][quantity]"
                                            type="number"
                                            class="input"
                                            placeholder="Quantity"
                                            value="{{ $returnDetail->quantity }}"
                                        >
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('return.0.quantity')
                                            <span
                                                class="help has-text-danger"
                                                role="alert"
                                            >
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button
                                            id="return[{{ $loop->index }}][product_id]Quantity"
                                            class="button bg-green has-text-white"
                                            type="button"
                                            x-text="product.unit_of_measurement"
                                        >
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label
                                    for="return[{{ $loop->index }}][unit_price]"
                                    class="label text-green has-text-weight-normal"
                                >Unit Price<sup class="has-text-weight-light"> ({{ userCompany()->getPriceMethod() }})</sup> <sup class="has-text-danger"></sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input
                                            id="return[{{ $loop->index }}][unit_price]"
                                            name="return[{{ $loop->index }}][unit_price]"
                                            type="number"
                                            class="input"
                                            placeholder="Sale Price"
                                            value="{{ $returnDetail->originalUnitPrice ?? '0.00' }}"
                                        >
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-money-bill"></i>
                                        </span>
                                        @error('return.0.unit_price')
                                            <span
                                                class="help has-text-danger"
                                                role="alert"
                                            >
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button
                                            id="return[{{ $loop->index }}][product_id]Price"
                                            class="button bg-green has-text-white"
                                            type="button"
                                            x-text="product.unit_of_measurement"
                                        >
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label
                                        for="return[{{ $loop->index }}][description]"
                                        class="label text-green has-text-weight-normal"
                                    >Additional Notes <sup class="has-text-danger"></sup></label>
                                    <div class="control has-icons-left">
                                        <textarea
                                            name="return[{{ $loop->index }}][description]"
                                            id="return[{{ $loop->index }}][description]"
                                            cols="30"
                                            rows="3"
                                            class="textarea pl-6"
                                            placeholder="Description or note to be taken"
                                        >{{ $returnDetail->description ?? '' }}</textarea>
                                        <span class="icon is-large is-left">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        @error('return.0.description')
                                            <span
                                                class="help has-text-danger"
                                                role="alert"
                                            >
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </x-content.main>

            @include('returns.details-form', ['data' => ['return' => $return->returnDetails]])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

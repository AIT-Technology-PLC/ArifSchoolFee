@extends('layouts.app')

@section('title', 'Create New Purchase')

@section('content')
    <x-common.content-wrapper x-data="purchaseInformation('{{ old('type') }}', '{{ old('tax_type') }}', '{{ old('currency') }}', {{ old('exchange_rate') }})">
        <x-content.header title="New Purchase" />
        <form
            id="formOne"
            action="{{ route('purchases.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Purchase No <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $currentPurchaseNo }}"
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
                            <x-forms.label for="purchased_on">
                                Purchase Date <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="date"
                                    name="purchased_on"
                                    id="purchased_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('purchased_on') ?? now()->toDateString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-day"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="purchased_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="type">
                                Purchase Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="type"
                                    name="type"
                                    x-model="purchaseType"
                                    x-on:change="changePurchaseInformation"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Type</option>
                                    <option value="Local Purchase">Local Purchase</option>
                                    <option value="Import">Import</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-shopping-bag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="payment_type">
                                Payment Method <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="payment_type"
                                    name="payment_type"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Payment</option>
                                    <option
                                        value="Cash Payment"
                                        @selected(old('payment_type') == 'Cash Payment')
                                    >Cash Payment</option>
                                    <option
                                        value="Credit Payment"
                                        @selected(old('payment_type') == 'Credit Payment')
                                    >Credit Payment</option>
                                    <option
                                        value="LC"
                                        @selected(old('payment_type') == 'LC')
                                    >LC</option>
                                    <option
                                        value="TT"
                                        @selected(old('payment_type') == 'TT')
                                    >TT</option>
                                    <option
                                        value="CAD"
                                        @selected(old('payment_type') == 'CAD')
                                    >CAD</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-credit-card"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="payment_type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="supplier_id">
                                Supplier <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="supplier_id"
                                    name="supplier_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option
                                            value="{{ $supplier->id }}"
                                            @selected(old('supplier_id') == $supplier->id)
                                        >{{ $supplier->company_name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-address-card"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="supplier_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': !isPurchaseByLocal() }"
                    >
                        <x-forms.field>
                            <x-forms.label for="tax_type">
                                Tax Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="tax_type"
                                    name="tax_type"
                                    x-model="taxType"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Tax Type</option>
                                    <option value="0.15">VAT</option>
                                    <option value="0.02">ToT</option>
                                    <option value="0">None</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-file-invoice-dollar"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="tax_type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': isPurchaseByLocal() }"
                    >
                        <x-forms.label for="currency">
                            Currency <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control>
                                <x-forms.select
                                    id="currency"
                                    name="currency"
                                    x-model="currency"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Currency</option>
                                    <option value="AED">AED - UAE Dirham</option>
                                    <option value="CHF">CHF - Swiss Frank</option>
                                    <option value="CNY">CNY - China Yuan</option>
                                    <option value="ETB">ETB - Ethiopian Birr</option>
                                    <option value="EUR">EUR - Euro Union Countries</option>
                                    <option value="GBP">GBP - GB Pound Sterling</option>
                                    <option value="SAR">SAR - Saudi Riyal</option>
                                    <option value="USD">USD - US Dollar</option>
                                    <option value="">None</option>
                                </x-forms.select>
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    name="exchange_rate"
                                    id="exchange_rate"
                                    placeholder="Exchange Rate"
                                    x-model="exchangeRate"
                                />
                                <x-common.icon
                                    name="fas fa-dollar-sign"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="currency" />
                                <x-common.validation-error property="exchange_rate" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="summernote textarea"
                                    placeholder="Description or note to be taken"
                                >{{ old('description') ?? '' }}
                                </x-forms.textarea>
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-content.main>

            @include('purchases.details-form', ['data' => session()->getOldInput()])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

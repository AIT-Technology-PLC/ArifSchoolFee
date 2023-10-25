@extends('layouts.app')

@section('title', 'Edit Purchase')

@section('content')
    <x-common.content-wrapper x-data="purchaseInformation(
        '{{ $purchase->type }}',
        '{{ $purchase->tax_id }}',
        '{{ $purchase->currency }}',
        '{{ $purchase->exchange_rate }}',
        '{{ $purchase->payment_type }}',
        '{{ $purchase->cash_paid_type }}',
        '{{ $purchase->cash_paid }}',
        '{{ $purchase->due_date?->toDateString() }}',
        '{{ $purchase->freight_cost }}',
        '{{ $purchase->freight_insurance_cost }}',
        '{{ $purchase->freight_unit }}',
        '{{ $purchase->other_costs_before_tax }}',
        '{{ $purchase->other_costs_after_tax }}',
        '{{ $purchase->isImported() ? $purchase->purchaseDetails->sum('amount') : '' }}')">
        <x-content.header title="Edit Purchase" />
        <form
            id="formOne"
            action="{{ route('purchases.update', $purchase->id) }}"
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
                                Purchase No <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    :readonly="!userCompany()->isEditingReferenceNumberEnabled()"
                                    value="{{ $purchase->code }}"
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
                                    value="{{ $purchase->purchased_on->toDateString() }}"
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
                                        value=""
                                    >
                                        Select Type
                                    </option>
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
                                    x-model="paymentType"
                                    x-on:change="changePaymentMethod"
                                >
                                    <option
                                        selected
                                        disabled
                                        value=""
                                    >Select Payment</option>
                                    <option
                                        x-show="isPurchaseByLocal()"
                                        value="Cash Payment"
                                    >Cash Payment</option>
                                    <option
                                        x-show="isPurchaseByLocal()"
                                        value="Credit Payment"
                                    >Credit Payment</option>
                                    <option
                                        x-show="isPurchaseByImport()"
                                        value="LC"
                                    >LC</option>
                                    <option
                                        x-show="isPurchaseByImport()"
                                        value="TT"
                                    >TT</option>
                                    <option
                                        x-show="isPurchaseByImport()"
                                        value="CAD"
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
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByLocal() && isPaymentInCredit()"
                    >
                        <x-forms.label for="cash_paid">
                            Cash Paid <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control>
                                <x-forms.select
                                    name="cash_paid_type"
                                    x-model="cashPaidType"
                                >
                                    <option
                                        selected
                                        disabled
                                        value=""
                                    >Type</option>
                                    <option value="amount">Amount</option>
                                    <option value="percent">Percent</option>
                                </x-forms.select>
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    name="cash_paid"
                                    id="cash_paid"
                                    placeholder="eg. 50"
                                    x-model="cashPaid"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="cash_paid" />
                                <x-common.validation-error property="cash_paid_type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByLocal() && isPaymentInCredit()"
                    >
                        <x-forms.field>
                            <x-forms.label for="due_date">
                                Credit Due Date <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="date"
                                    name="due_date"
                                    id="due_date"
                                    placeholder="mm/dd/yyyy"
                                    x-model="dueDate"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="due_date" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (isFeatureEnabled('Supplier Management'))
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="supplier_id">
                                    Supplier <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left select is-fullwidth">
                                    <x-common.supplier-list :selected-id="old('supplier_id', $purchase->supplier_id)" />
                                    <x-common.icon
                                        name="fas fa-address-card"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="supplier_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="contact_id">
                                Contact <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="select is-fullwidth has-icons-left">
                                <x-common.contact-list :selected-id="$purchase->contact_id ?? ''" />
                                <x-common.icon
                                    name="fas fa-address-card"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByLocal()"
                    >
                        <x-forms.field>
                            <x-forms.label for="tax_id">
                                Tax Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="tax_id"
                                    name="tax_id"
                                    x-model="taxId"
                                >
                                    <option
                                        selected
                                        disabled
                                        value=""
                                    >Select Tax Type</option>
                                    @foreach ($taxTypes as $taxType)
                                        <option
                                            value="{{ $taxType->id }}"
                                            {{ old('tax_id', $purchase->tax_id) == $taxType->id ? 'selected' : '' }}
                                        >{{ $taxType->type }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-file-invoice-dollar"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="tax_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
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
                                <x-common.validation-error property="currency" />
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
                                <x-common.validation-error property="exchange_rate" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.field>
                            <x-forms.label for="freight_cost">
                                Freight Cost <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    id="freight_cost"
                                    name="freight_cost"
                                    placeholder="Freight Cost"
                                    x-model="freightCost"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="freight_cost" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.field>
                            <x-forms.label for="freight_insurance_cost">
                                Freight Insurance Cost <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    id="freight_insurance_cost"
                                    name="freight_insurance_cost"
                                    placeholder="Freight Insurance Cost"
                                    x-model="freightInsuranceCost"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="freight_insurance_cost" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.label for="freight_amount">
                            Freight Volume <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    name="freight_amount"
                                    id="freight_amount"
                                    placeholder="Total Freight Volume"
                                    x-model="freightAmount"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="freight_amount" />
                            </x-forms.control>
                            <x-forms.control>
                                <x-forms.select
                                    name="freight_unit"
                                    x-model="freightUnit"
                                >
                                    <x-common.measurement-unit-options />
                                    <option value="">None</option>
                                </x-forms.select>
                                <x-common.validation-error property="freight_unit" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.field>
                            <x-forms.label for="other_costs_before_tax">
                                Other Costs Before Tax <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    id="other_costs_before_tax"
                                    name="other_costs_before_tax"
                                    placeholder="Other Costs Before Tax"
                                    x-model="otherCostsBeforeTax"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="other_costs_before_tax" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-show="isPurchaseByImport()"
                    >
                        <x-forms.field>
                            <x-forms.label for="other_costs_after_tax">
                                Other Costs After Tax <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    id="other_costs_after_tax"
                                    name="other_costs_after_tax"
                                    placeholder="Other Costs After Tax"
                                    x-model="otherCostsAfterTax"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="other_costs_after_tax" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <x-common.custom-field-form
                        model-type="purchase"
                        :input="old('customField') ?? $purchase->customFieldsAsKeyValue()"
                    />
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
                                >{{ $purchase->description }}
                                </x-forms.textarea>
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-content.main>

            @include('purchases.details-form', ['data' => ['purchase' => old('purchase') ?? $purchase->purchaseDetails]])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

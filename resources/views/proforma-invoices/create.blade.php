@extends('layouts.app')

@section('title', 'Create Proforma Invoice')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Proforma Invoice" />
        <form
            id="formOne"
            action="{{ route('proforma-invoices.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.label for="code">
                            PI No <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="is-expanded">
                                <x-forms.input
                                    name="prefix"
                                    type="text"
                                    placeholder="Prefix"
                                    value="{{ userCompany()->proforma_invoice_prefix ?? '' }}"
                                />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    :readonly="!userCompany()->isEditingReferenceNumberEnabled()"
                                    value="{{ $currentProformaInvoiceCode }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (isFeatureEnabled('Customer Management'))
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="customer_id">
                                    Customer <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="select is-fullwidth has-icons-left">
                                    <x-common.customer-list :selected-id="old('customer_id') ?? ''" />
                                    <x-common.icon
                                        name="fas fa-address-card"
                                        class="is-small is-left"
                                    />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="issued_on">
                                Issued On <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="datetime-local"
                                    name="issued_on"
                                    id="issued_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('issued_on') ?? now()->toDateTimeLocalString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="issued_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="contact_id">
                                Contact <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left select is-fullwidth">
                                <x-common.contact-list :selected-id="old('contact_id') ?? ''" />
                                <x-common.icon
                                    name="fas fa-address-card"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="contact_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="expires_on">
                                Expiry Date <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="date"
                                    name="expires_on"
                                    id="expires_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('expires_on') ??
                                        now()->addDays(10)->toDateString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="expires_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6 {{ userCompany()->isDiscountBeforeTax() ? 'is-hidden' : '' }}">
                        <x-forms.label for="discount">
                            Discount<sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    id="discount"
                                    name="discount"
                                    type="number"
                                    placeholder="Discount in Percentage"
                                    value="{{ old('discount') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="discount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <x-common.custom-field-form
                        model-type="proformainvoice"
                        :input="old('customField')"
                    />
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="terms">
                                Terms & Conditions <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.textarea
                                    name="terms"
                                    id="terms"
                                    rows="5"
                                    class="summernote textarea"
                                    placeholder="Description or note to be taken"
                                >{{ old('terms') ?? '' }}</x-forms.textarea>
                                <x-common.validation-error property="terms" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-content.main>

            @include('proforma-invoices.partials.details-form', ['data' => session()->getOldInput()])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>

    @can('Create Customer')
        <x-common.customer-form-modal />
    @endcan
@endsection

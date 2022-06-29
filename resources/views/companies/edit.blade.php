@extends('layouts.app')

@section('title', 'Edit Settings')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit General Settings" />
        <form
            id="formOne"
            action="{{ route('companies.update', userCompany()->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <x-common.success-message :message="session('successMessage')" />
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="name">
                                Name <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="name"
                                    type="text"
                                    placeholder="Company Name"
                                    value="{{ $company->name }}"
                                    disabled
                                />
                                <x-common.icon
                                    name="fas fa-building"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="currency">
                                Currency <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="currency"
                                    name="currency"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Currency</option>
                                    <option
                                        value="AED"
                                        {{ $company->currency == 'AED' ? 'selected' : '' }}
                                    >AED - UAE Dirham</option>
                                    <option
                                        value="CHF"
                                        {{ $company->currency == 'CHF' ? 'selected' : '' }}
                                    >CHF - Swiss Frank</option>
                                    <option
                                        value="CNY"
                                        {{ $company->currency == 'CNY' ? 'selected' : '' }}
                                    >CNY - China Yuan</option>
                                    <option
                                        value="ETB"
                                        {{ $company->currency == 'ETB' ? 'selected' : '' }}
                                    >ETB - Ethiopian Birr</option>
                                    <option
                                        value="EUR"
                                        {{ $company->currency == 'EUR' ? 'selected' : '' }}
                                    >EUR - Euro Union Countries</option>
                                    <option
                                        value="GBP"
                                        {{ $company->currency == 'GBP' ? 'selected' : '' }}
                                    >GBP - GB Pound Sterling</option>
                                    <option
                                        value="SAR"
                                        {{ $company->currency == 'SAR' ? 'selected' : '' }}
                                    >SAR - Saudi Riyal</option>
                                    <option
                                        value="USD"
                                        {{ $company->currency == 'USD' ? 'selected' : '' }}
                                    >USD - US Dollar</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-dollar-sign"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="email">
                                Email <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="email"
                                    name="email"
                                    type="text"
                                    placeholder="Email Address"
                                    value="{{ $company->email ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-at"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="email" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="phone">
                                Phone <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    placeholder="Phone/Telephone"
                                    value="{{ $company->phone ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-phone"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="phone" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="address">
                                Address <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="address"
                                    name="address"
                                    type="text"
                                    placeholder="Address"
                                    value="{{ $company->address ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-map-marker-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="address" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="sector">
                                Business Sector <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="sector"
                                    name="sector"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Sector</option>
                                    <option
                                        value="Manufacturer"
                                        {{ $company->sector == 'Manufacturer' ? 'selected' : '' }}
                                    >Manufacturer</option>
                                    <option
                                        value="Wholesaler"
                                        {{ $company->sector == 'Wholesaler' ? 'selected' : '' }}
                                    >Wholesaler</option>
                                    <option
                                        value="Processor"
                                        {{ $company->sector == 'Processor' ? 'selected' : '' }}
                                    >Processor</option>
                                    <option
                                        value="Retailer"
                                        {{ $company->sector == 'Retailer' ? 'selected' : '' }}
                                    >Retailer</option>
                                    <option value="">None</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-city"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-data="UploadedFileNameHandler"
                    >
                        <x-forms.field>
                            <x-forms.label for="logo">
                                Logo <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <div class="file has-name">
                                <label class="file-label">
                                    <x-forms.input
                                        class="file-input"
                                        type="file"
                                        name="logo"
                                        x-model="file"
                                        x-on:change="getFileName"
                                    />
                                    <span class="file-cta bg-green has-text-white">
                                        <x-common.icon
                                            name="fas fa-upload"
                                            class="file-icon"
                                        />
                                        <span class="file-label">
                                            Upload Logo
                                        </span>
                                    </span>
                                    <span
                                        class="file-name"
                                        x-text="fileName || '{{ $company->logo }}' || 'Select File...'"
                                    >
                                    </span>
                                </label>
                            </div>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="proforma_invoice_prefix">
                                Proforma Invoice Prefix <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="proforma_invoice_prefix"
                                    name="proforma_invoice_prefix"
                                    type="text"
                                    placeholder="eg. AB/21"
                                    value="{{ $company->proforma_invoice_prefix ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-font"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="proforma_invoice_prefix" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_price_before_vat">
                                Unit Price Method <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.label class="radio">
                                    <input
                                        type="radio"
                                        name="is_price_before_vat"
                                        value="1"
                                        class="mt-3"
                                        {{ $company->is_price_before_vat ? 'checked' : '' }}
                                    >
                                    Before VAT
                                </x-forms.label>
                                <x-forms.label class="radio ml-0">
                                    <input
                                        type="radio"
                                        name="is_price_before_vat"
                                        value="0"
                                        {{ $company->is_price_before_vat ? '' : 'checked' }}
                                    >
                                    After VAT
                                </x-forms.label>
                                <x-common.validation-error property="is_price_before_vat" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_discount_before_vat">
                                Discount Method <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.label class="radio">
                                    <input
                                        type="radio"
                                        name="is_discount_before_vat"
                                        value="1"
                                        class="mt-3"
                                        {{ $company->is_discount_before_vat ? 'checked' : '' }}
                                    >
                                    Before VAT & Per Product
                                </x-forms.label>
                                <x-forms.label class="radio ml-0">
                                    <input
                                        type="radio"
                                        name="is_discount_before_vat"
                                        value="0"
                                        {{ $company->is_discount_before_vat ? '' : 'checked' }}
                                    >
                                    After Grand Total Price
                                </x-forms.label>
                                <x-common.validation-error property="is_discount_before_vat" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_convert_to_siv_as_approved">
                                Convert to SIV as <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.label class="radio">
                                    <input
                                        type="radio"
                                        name="is_convert_to_siv_as_approved"
                                        value="1"
                                        class="mt-3"
                                        {{ $company->is_convert_to_siv_as_approved ? 'checked' : '' }}
                                    >
                                    Approved
                                </x-forms.label>
                                <x-forms.label class="radio ml-0">
                                    <input
                                        type="radio"
                                        name="is_convert_to_siv_as_approved"
                                        value="0"
                                        {{ $company->is_convert_to_siv_as_approved ? '' : 'checked' }}
                                    >
                                    Not approved
                                </x-forms.label>
                                <x-common.validation-error property="is_convert_to_siv_as_approved" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="can_show_branch_detail_on_print">
                                Can Show Branch Detail On Print <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.label class="radio">
                                    <input
                                        type="radio"
                                        name="can_show_branch_detail_on_print"
                                        value="1"
                                        class="mt-3"
                                        {{ $company->can_show_branch_detail_on_print ? 'checked' : '' }}
                                    >
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio ml-0">
                                    <input
                                        type="radio"
                                        name="can_show_branch_detail_on_print"
                                        value="0"
                                        {{ $company->can_show_branch_detail_on_print ? '' : 'checked' }}
                                    >
                                    No
                                </x-forms.label>
                                <x-common.validation-error property="can_show_branch_detail_on_print" />
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

@extends('layouts.app')

@section('title', 'Edit Settings')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Settings" />
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
                            <x-forms.label for="tin">
                                TIN <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    name="tin"
                                    id="tin"
                                    type="number"
                                    placeholder="TIN"
                                    value="{{ old('tin', $company->tin) }}"
                                />
                                <x-common.icon
                                    name="fas fa-building"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="tin" />
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
                            <x-common.validation-error property="logo" />
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-data="UploadedFileNameHandler"
                    >
                        <x-forms.field>
                            <x-forms.label for="print_template_image">
                                Print Template <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <div class="file has-name">
                                <label class="file-label">
                                    <x-forms.input
                                        class="file-input"
                                        type="file"
                                        name="print_template_image"
                                        x-model="file"
                                        x-on:change="getFileName"
                                    />
                                    <span class="file-cta bg-green has-text-white">
                                        <x-common.icon
                                            name="fas fa-upload"
                                            class="file-icon"
                                        />
                                        <span class="file-label">
                                            Upload Template
                                        </span>
                                    </span>
                                    <span
                                        class="file-name"
                                        x-text="fileName || '{{ $company->print_template_image }}' || 'Select File...'"
                                    >
                                    </span>
                                </label>
                            </div>
                            <x-common.validation-error property="print_template_image" />
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Print Padding (%) <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="print_padding_top"
                                    name="print_padding_top"
                                    type="number"
                                    placeholder="Top Padding"
                                    value="{{ $company->print_padding_top }}"
                                />
                                <x-common.icon
                                    name="fas fa-arrow-up"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="print_padding_top" />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="print_padding_bottom"
                                    name="print_padding_bottom"
                                    type="number"
                                    placeholder="Bottom Padding"
                                    value="{{ $company->print_padding_bottom }}"
                                />
                                <x-common.icon
                                    name="fas fa-arrow-down"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="print_padding_bottom" />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="print_padding_horizontal"
                                    name="print_padding_horizontal"
                                    type="number"
                                    placeholder="Horizontal Padding"
                                    value="{{ $company->print_padding_horizontal }}"
                                />
                                <x-common.icon
                                    name="fas fa-arrows-left-right"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="print_padding_horizontal" />
                            </x-forms.control>
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
                                <label class="radio has-text-grey">
                                    <input
                                        type="radio"
                                        name="is_price_before_vat"
                                        value="1"
                                        class="mt-3"
                                        {{ $company->is_price_before_vat ? 'checked' : '' }}
                                    >
                                    Before VAT
                                </label>
                                <label class="radio has-text-grey mt-2">
                                    <input
                                        type="radio"
                                        name="is_price_before_vat"
                                        value="0"
                                        {{ $company->is_price_before_vat ? '' : 'checked' }}
                                    >
                                    After VAT
                                </label>
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
                                <label class="radio has-text-grey">
                                    <input
                                        type="radio"
                                        name="is_discount_before_vat"
                                        value="1"
                                        class="mt-3"
                                        {{ $company->is_discount_before_vat ? 'checked' : '' }}
                                    >
                                    Before VAT & Per Product
                                </label>
                                <br>
                                <label class="radio has-text-grey mt-2">
                                    <input
                                        type="radio"
                                        name="is_discount_before_vat"
                                        value="0"
                                        {{ $company->is_discount_before_vat ? '' : 'checked' }}
                                    >
                                    After Grand Total Price
                                </label>
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
                                <label class="radio has-text-grey">
                                    <input
                                        type="radio"
                                        name="is_convert_to_siv_as_approved"
                                        value="1"
                                        class="mt-3"
                                        {{ $company->is_convert_to_siv_as_approved ? 'checked' : '' }}
                                    >
                                    Approved
                                </label>
                                <br>
                                <label class="radio has-text-grey mt-2">
                                    <input
                                        type="radio"
                                        name="is_convert_to_siv_as_approved"
                                        value="0"
                                        {{ $company->is_convert_to_siv_as_approved ? '' : 'checked' }}
                                    >
                                    Not approved
                                </label>
                                <x-common.validation-error property="is_convert_to_siv_as_approved" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_editing_reference_number_enabled">
                                Editing Reference Numbers <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey">
                                    <input
                                        type="radio"
                                        name="is_editing_reference_number_enabled"
                                        value="1"
                                        class="mt-3"
                                        @checked($company->isEditingReferenceNumberEnabled())
                                    >
                                    Enabled
                                </label>
                                <br>
                                <label class="radio has-text-grey mt-2">
                                    <input
                                        type="radio"
                                        name="is_editing_reference_number_enabled"
                                        value="0"
                                        @checked(!$company->isEditingReferenceNumberEnabled())
                                    >
                                    Disabled
                                </label>
                                <x-common.validation-error property="is_editing_reference_number_enabled" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="can_show_branch_detail_on_print">
                                Can Show Branch Detail On Print <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey">
                                    <input
                                        type="radio"
                                        name="can_show_branch_detail_on_print"
                                        value="1"
                                        class="mt-3"
                                        {{ $company->can_show_branch_detail_on_print ? 'checked' : '' }}
                                    >
                                    Yes
                                </label>
                                <br>
                                <label class="radio has-text-grey mt-2">
                                    <input
                                        type="radio"
                                        name="can_show_branch_detail_on_print"
                                        value="0"
                                        {{ $company->can_show_branch_detail_on_print ? '' : 'checked' }}
                                    >
                                    No
                                </label>
                                <x-common.validation-error property="can_show_branch_detail_on_print" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_backorder_enabled">
                                Backorder <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey">
                                    <input
                                        type="radio"
                                        name="is_backorder_enabled"
                                        value="1"
                                        class="mt-3"
                                        @checked($company->isBackorderEnabled())
                                    >
                                    Enabled
                                </label>
                                <br>
                                <label class="radio has-text-grey mt-2">
                                    <input
                                        type="radio"
                                        name="is_backorder_enabled"
                                        value="0"
                                        @checked(!$company->isBackorderEnabled())
                                    >
                                    Disabled
                                </label>
                                <x-common.validation-error property="is_backorder_enabled" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="can_check_inventory_on_forms">
                                Allow Inventory Checking On Forms <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey">
                                    <input
                                        type="radio"
                                        name="can_check_inventory_on_forms"
                                        value="1"
                                        class="mt-3"
                                        @checked($company->isInventoryCheckerEnabled())
                                    >
                                    Enabled
                                </label>
                                <br>
                                <label class="radio has-text-grey mt-2">
                                    <input
                                        type="radio"
                                        name="can_check_inventory_on_forms"
                                        value="0"
                                        @checked(!$company->isInventoryCheckerEnabled())
                                    >
                                    Disabled
                                </label>
                                <x-common.validation-error property="can_check_inventory_on_forms" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="income_tax_region">
                                Income Tax Region <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="income_tax_region"
                                    name="income_tax_region"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Region</option>
                                    <option
                                        value="Ethiopia"
                                        @selected($company->income_tax_region == 'Ethiopia')
                                    >Ethiopia</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-city"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="income_tax_region" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Payroll Bank Details <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="payroll_bank_name"
                                    name="payroll_bank_name"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Bank
                                    </option>
                                    @if (old('payroll_bank_name', $company->payroll_bank_name))
                                        <option
                                            value="{{ old('payroll_bank_name', $company->payroll_bank_name) }}"
                                            selected
                                        >
                                            {{ old('payroll_bank_name', $company->payroll_bank_name) }}
                                        </option>
                                    @endif
                                    @include('lists.banks')
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-university"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="payroll_bank_name" />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="payroll_bank_account_number"
                                    name="payroll_bank_account_number"
                                    type="text"
                                    placeholder="Bank Account"
                                    value="{{ old('payroll_bank_account_number', $company->payroll_bank_account_number) }}"
                                    autocomplete="payroll_bank_account_number"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="payroll_bank_account_number" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="paid_time_off_amount">
                                Paid Time Off Amount <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="paid_time_off_amount"
                                    name="paid_time_off_amount"
                                    type="number"
                                    placeholder="Paid Time Off Amount"
                                    value="{{ $company->paid_time_off_amount ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="paid_time_off_amount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="paid_time_off_type">
                                Paid Time Off Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="paid_time_off_type"
                                    name="paid_time_off_type"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Paid Time Off Type</option>
                                    <option
                                        value="Days"
                                        @selected($company->paid_time_off_type == 'Days')
                                    >Days</option>
                                    <option
                                        value="Hours"
                                        @selected($company->paid_time_off_type == 'Hours')
                                    >Hours</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="paid_time_off_type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="working_days">
                                Working Days <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="working_days"
                                    name="working_days"
                                    type="number"
                                    min="1"
                                    max="30"
                                    placeholder="Working Days"
                                    value="{{ $company->working_days ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="working_days" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="sales_report_source">
                                Sales Report Source <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="sales_report_source"
                                    name="sales_report_source"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Source</option>
                                    <option
                                        value="All Delivery Orders"
                                        @selected($company->sales_report_source == 'All Delivery Orders')
                                    >All Delivery Orders</option>
                                    <option
                                        value="Approved & Subtracted Delivery Orders"
                                        @selected($company->sales_report_source == 'Approved & Subtracted Delivery Orders')
                                    >Approved & Subtracted Delivery Orders</option>
                                    <option
                                        value="Subtracted Delivery Orders"
                                        @selected($company->sales_report_source == 'Subtracted Delivery Orders')
                                    >Subtracted Delivery Orders</option>
                                    <option
                                        value="All Invoices"
                                        @selected($company->sales_report_source == 'All Invoices')
                                    >All Invoices</option>
                                    <option
                                        value="Approved Invoices"
                                        @selected($company->sales_report_source == 'Approved Invoices')
                                    >Approved Invoices</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="sales_report_source" />
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

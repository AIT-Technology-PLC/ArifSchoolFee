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
                                        value="DJF"
                                        {{ $company->currency == 'DJF' ? 'selected' : '' }}
                                    >DJF - Djibouti Franc</option>
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
                            <x-forms.label for="is_in_training">
                                Usage Mode <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="is_in_training"
                                    name="is_in_training"
                                >
                                    <option
                                        value="1"
                                        @selected($company->isInTraining())
                                    > Training (Testing) </option>
                                    <option
                                        value="0"
                                        @selected(!$company->isInTraining())
                                    > Live </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-building-lock"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="is_in_training" />
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
                            <x-forms.label for="can_show_branch_detail_on_print">
                                Show Branch Detail On Print <sup class="has-text-danger">*</sup>
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
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

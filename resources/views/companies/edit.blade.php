@extends('layouts.app')

@section('title')
    Edit Settings
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit General Settings
            </h1>
        </div>
        <form id="formOne" action="{{ route('companies.update', userCompany()->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="name" class="label text-green has-text-weight-normal">Name <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="name" type="text" class="input" placeholder="Company Name" value="{{ $company->name }}" disabled>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-building"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="currency" class="label text-green has-text-weight-normal"> Currency <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="currency" name="currency">
                                        <option selected disabled>Select Currency</option>
                                        <option value="AED" {{ $company->currency == 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                                        <option value="CHF" {{ $company->currency == 'CHF' ? 'selected' : '' }}>CHF - Swiss Frank</option>
                                        <option value="CNY" {{ $company->currency == 'CNY' ? 'selected' : '' }}>CNY - China Yuan</option>
                                        <option value="ETB" {{ $company->currency == 'ETB' ? 'selected' : '' }}>ETB - Ethiopian Birr</option>
                                        <option value="EUR" {{ $company->currency == 'EUR' ? 'selected' : '' }}>EUR - Euro Union Countries</option>
                                        <option value="GBP" {{ $company->currency == 'GBP' ? 'selected' : '' }}>GBP - GB Pound Sterling</option>
                                        <option value="SAR" {{ $company->currency == 'SAR' ? 'selected' : '' }}>SAR - Saudi Riyal</option>
                                        <option value="USD" {{ $company->currency == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="email" class="label text-green has-text-weight-normal">Email <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="email" name="email" type="text" class="input" placeholder="Email Address" value="{{ $company->email ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-at"></i>
                                </span>
                                @error('email')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="phone" class="label text-green has-text-weight-normal">Phone <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="phone" name="phone" type="text" class="input" placeholder="Phone/Telephone" value="{{ $company->phone ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-phone"></i>
                                </span>
                                @error('phone')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="address" class="label text-green has-text-weight-normal">Address <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="address" name="address" type="text" class="input" placeholder="Address" value="{{ $company->address ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                @error('address')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="sector" class="label text-green has-text-weight-normal"> Business Sector <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="sector" name="sector">
                                        <option selected disabled>Select Sector</option>
                                        <option value="Manufacturer" {{ $company->sector == 'Manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                                        <option value="Wholesaler" {{ $company->sector == 'Wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                                        <option value="Processor" {{ $company->sector == 'Processor' ? 'selected' : '' }}>Processor</option>
                                        <option value="Retailer" {{ $company->sector == 'Retailer' ? 'selected' : '' }}>Retailer</option>
                                        <option value="">None</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-city"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="logo" class="label text-green has-text-weight-normal"> Logo <sup class="has-text-danger"></sup> </label>
                            <div class="file has-name">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="logo">
                                    <span class="file-cta bg-green has-text-white">
                                        <span class="file-icon">
                                            <i class="fas fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                            Upload Logo
                                        </span>
                                    </span>
                                    <span class="file-name">
                                        {{ $company->logo ?? '' }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="proforma_invoice_prefix" class="label text-green has-text-weight-normal">Proforma Invoice Prefix <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="proforma_invoice_prefix" name="proforma_invoice_prefix" type="text" class="input" placeholder="eg. AB/21" value="{{ $company->proforma_invoice_prefix ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-font"></i>
                                </span>
                                @error('proforma_invoice_prefix')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="is_price_before_vat" class="label text-green has-text-weight-normal"> Unit Price Method <sup class="has-text-danger">*</sup> </label>
                            <div class="control">
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input type="radio" name="is_price_before_vat" value="1" class="mt-3" {{ $company->is_price_before_vat ? 'checked' : '' }}>
                                    Before VAT
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input type="radio" name="is_price_before_vat" value="0" {{ $company->is_price_before_vat ? '' : 'checked' }}>
                                    After VAT
                                </label>
                                @error('is_price_before_vat')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="is_discount_before_vat" class="label text-green has-text-weight-normal"> Discount Method <sup class="has-text-danger">*</sup> </label>
                            <div class="control">
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input type="radio" name="is_discount_before_vat" value="1" class="mt-3" {{ $company->is_discount_before_vat ? 'checked' : '' }}>
                                    Before VAT & Per Product
                                </label>
                                <br>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input type="radio" name="is_discount_before_vat" value="0" {{ $company->is_discount_before_vat ? '' : 'checked' }}>
                                    After Grand Total Price
                                </label>
                                @error('is_discount_before_vat')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box radius-top-0">
                <div class="columns is-marginless">
                    <div class="column is-paddingless">
                        <div class="buttons is-right">
                            <button class="button is-white text-green" type="reset">
                                <span class="icon">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span>
                                    Cancel
                                </span>
                            </button>
                            <button id="saveButton" class="button bg-green has-text-white">
                                <span class="icon">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span>
                                    Save
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

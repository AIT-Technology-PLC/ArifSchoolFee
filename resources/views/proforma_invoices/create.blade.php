@extends('layouts.app')

@section('title')
    Create New Proforma Invoice
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Proforma Invoice
            </h1>
        </div>
        <form id="formOne" action="{{ route('proforma-invoices.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <label for="code" class="label text-green has-text-weight-normal">PI No <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control">
                                <input name="prefix" class="input" type="text" placeholder="Prefix" value="{{ userCompany()->proforma_invoice_prefix ?? '' }}">
                            </div>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="code" id="code" value="{{ $currentProformaInvoiceCode + 1 }}">
                                <span class="icon is-large is-left">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                @error('code')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="customer_id" class="label text-green has-text-weight-normal"> Customer <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="customer_id" name="customer_id">
                                        <option selected disabled>Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->company_name }}</option>
                                        @endforeach
                                        <option value="">None</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-address-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="issued_on" class="label text-green has-text-weight-normal"> Issued On <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="issued_on" id="issued_on" placeholder="mm/dd/yyyy" value="{{ old('issued_on') ?? now()->toDateString() }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                @error('issued_on')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="expires_on" class="label text-green has-text-weight-normal"> Expiry Date <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="expires_on" id="expires_on" placeholder="mm/dd/yyyy" value="{{ old('expires_on') ??
    now()->addDays(10)->toDateString() }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                @error('expires_on')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="field">
                            <label for="terms" class="label text-green has-text-weight-normal">Terms & Conditions <sup class="has-text-danger"></sup> </label>
                            <div class="control">
                                <textarea name="terms" id="terms" cols="30" rows="5" class="summernote textarea" placeholder="Description or note to be taken">{{ old('terms') ?? '' }}</textarea>
                                @error('terms')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="has-text-weight-medium has-text-left mt-5">
                    <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                        Item 1
                    </span>
                </div>
                <div class="box has-background-white-bis radius-top-0">
                    <div name="proformaInvoiceFormGroup" class="columns is-marginless is-multiline">
                        <div class="column is-6">
                            <div class="field">
                                <label for="proformaInvoice[0][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <x-product-list name="proformaInvoice[0]" selected-product-id="{{ old('proformaInvoice.0.product_id') }}" />
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-th"></i>
                                    </div>
                                    @error('proformaInvoice.0.product_id')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="proformaInvoice[0][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="proformaInvoice[0][quantity]" name="proformaInvoice[0][quantity]" type="number" class="input" placeholder="Product Quantity" value="{{ old('proformaInvoice.0.quantity') ?? '' }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-balance-scale"></i>
                                    </span>
                                    @error('proformaInvoice.0.quantity')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="control">
                                    <button id="proformaInvoice[0][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="proformaInvoice[0][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <sup class="has-text-danger">*</sup> </label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="proformaInvoice[0][unit_price]" name="proformaInvoice[0][unit_price]" type="number" class="input" placeholder="Unit Price" value="{{ old('proformaInvoice.0.unit_price') ?? '' }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-money-bill"></i>
                                    </span>
                                    @error('proformaInvoice.0.unit_price')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="control">
                                    <button id="proformaInvoice[0][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="proformaInvoice[0][discount]" class="label text-green has-text-weight-normal">Discount <sup class="has-text-danger"></sup> </label>
                            <div class="field">
                                <div class="control has-icons-left is-expanded">
                                    <input id="proformaInvoice[0][discount]" name="proformaInvoice[0][discount]" type="number" class="input" placeholder="Discount in Percentage" value="{{ old('proformaInvoice.0.discount') ?? '' }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-percent"></i>
                                    </span>
                                    @error('proformaInvoice.0.discount')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="field">
                                <label for="specification" class="label text-green has-text-weight-normal">Specifications <sup class="has-text-danger"></sup> </label>
                                <div class="control">
                                    <textarea name="specification" id="specification" cols="30" rows="5" class="summernote textarea" placeholder="Description or note to be taken">{{ old('specification') ?? '' }}</textarea>
                                    @error('specification')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach (range(1, 10) as $i)
                    @if (old('proformaInvoice.' . $i . '.product_id') || old('proformaInvoice.' . $i . '.quantity') || old('proformaInvoice.' . $i . '.unit_price') || old('proformaInvoice.' . $i . '.discount'))
                        <div class="has-text-weight-medium has-text-left">
                            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                                Item {{ $i + 1 }}
                            </span>
                        </div>
                        <div class="box has-background-white-bis radius-top-0">
                            <div name="proformaInvoiceFormGroup" class="columns is-marginless is-multiline">
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="proformaInvoice[{{ $i }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                        <div class="control has-icons-left">
                                            <x-product-list name="proformaInvoice[{{ $i }}]" selected-product-id="{{ old('proformaInvoice.' . $i . '.product_id') }}" />
                                            <div class="icon is-small is-left">
                                                <i class="fas fa-th"></i>
                                            </div>
                                            @error('proformaInvoice.' . $i . '.product_id')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <label for="proformaInvoice[{{ $i }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                    <div class="field has-addons">
                                        <div class="control has-icons-left is-expanded">
                                            <input id="proformaInvoice[{{ $i }}][quantity]" name="proformaInvoice[{{ $i }}][quantity]" type="number" class="input" placeholder="Product Quantity" value="{{ old('proformaInvoice.' . $i . '.quantity') ?? '' }}">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-balance-scale"></i>
                                            </span>
                                            @error('proformaInvoice.' . $i . '.quantity')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="control">
                                            <button id="proformaInvoice[{{ $i }}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <label for="proformaInvoice[{{ $i }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <sup class="has-text-danger">*</sup> </label>
                                    <div class="field has-addons">
                                        <div class="control has-icons-left is-expanded">
                                            <input id="proformaInvoice[{{ $i }}][unit_price]" name="proformaInvoice[{{ $i }}][unit_price]" type="number" class="input" placeholder="Unit Price" value="{{ old('proformaInvoice.' . $i . '.unit_price') ?? '' }}">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-money-bill"></i>
                                            </span>
                                            @error('proformaInvoice.' . $i . '.unit_price')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="control">
                                            <button id="proformaInvoice[{{ $i }}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <label for="proformaInvoice[{{ $i }}][discount]" class="label text-green has-text-weight-normal">Discount<sup class="has-text-danger"></sup> </label>
                                    <div class="field">
                                        <div class="control has-icons-left is-expanded">
                                            <input id="proformaInvoice[{{ $i }}][discount]" name="proformaInvoice[{{ $i }}][discount]" type="number" class="input" placeholder="Discount in Percentage" value="{{ old('proformaInvoice.' . $i . '.discount') ?? '' }}">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-percent"></i>
                                            </span>
                                            @error('proformaInvoice.' . $i . '.discount')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="field">
                                        <label for="proformaInvoice[{{ $i }}][specification]" class="label text-green has-text-weight-normal">Specifications <sup class="has-text-danger"></sup> </label>
                                        <div class="control">
                                            <textarea name="proformaInvoice[{{ $i }}][specification]" id="proformaInvoice[{{ $i }}][specification]" cols="30" rows="5" class="summernote textarea"
                                                placeholder="Specification about the product"> {{ old('proformaInvoice.' . $i . '.specification') ?? '' }} </textarea>
                                            @error('proformaInvoice.' . $i . '.specification')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                <div id="proformaInvoiceFormWrapper"></div>
                <button id="addNewProformaInvoiceForm" type="button" class="button bg-purple has-text-white is-small ml-3 mt-3">
                    Add More Item
                </button>
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

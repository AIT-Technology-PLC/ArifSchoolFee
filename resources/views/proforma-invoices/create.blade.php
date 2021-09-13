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
                    <div class="column is-6 {{ userCompany()->isDiscountBeforeVAT() ? 'is-hidden' : '' }}">
                        <label for="discount" class="label text-green has-text-weight-normal">Discount<sup class="has-text-danger"></sup> </label>
                        <div class="field">
                            <div class="control has-icons-left is-expanded">
                                <input id="discount" name="discount" type="number" class="input" placeholder="Discount in Percentage" value="{{ old('discount') ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-percent"></i>
                                </span>
                                @error('discount')
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
                <div id="proforma-invoice-details">
                    @foreach (old('proformaInvoice', [0]) as $proformaInvoiceDetail)
                        <div class="proforma-invoice-detail mx-3">
                            <div class="has-text-weight-medium has-text-left mt-5">
                                <span name="item-number" class="tag bg-green has-text-white is-medium radius-bottom-0">
                                    Item {{ $loop->iteration }}
                                </span>
                            </div>
                            <div class="box has-background-white-bis radius-top-0">
                                <div class="columns is-marginless is-multiline">
                                    <div class="column is-6">
                                        <div class="field">
                                            <label for="proformaInvoice[{{ $loop->index }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                            <div class="control has-icons-left">
                                                <x-product-list tags="true" name="proformaInvoice[{{ $loop->index }}]" selected-product-id="{{ $proformaInvoiceDetail['product_id'] ?? '' }}" />
                                                <div class="icon is-small is-left">
                                                    <i class="fas fa-th"></i>
                                                </div>
                                                @error('proformaInvoice.' . $loop->index . '.product_id')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <label for="proformaInvoice[{{ $loop->index }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                        <div class="field has-addons">
                                            <div class="control has-icons-left is-expanded">
                                                <input id="proformaInvoice[{{ $loop->index }}][quantity]" name="proformaInvoice[{{ $loop->index }}][quantity]" type="number" class="input" placeholder="Product Quantity" value="{{ $proformaInvoiceDetail['quantity'] ?? '' }}">
                                                <span class="icon is-small is-left">
                                                    <i class="fas fa-balance-scale"></i>
                                                </span>
                                                @error('proformaInvoice.' . $loop->index . '.quantity')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="control">
                                                <button id="proformaInvoice[{{ $loop->index }}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <label for="proformaInvoice[{{ $loop->index }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> ({{ userCompany()->getPriceMethod() }})</sup> <sup class="has-text-danger">*</sup> </label>
                                        <div class="field has-addons">
                                            <div class="control has-icons-left is-expanded">
                                                <input id="proformaInvoice[{{ $loop->index }}][unit_price]" name="proformaInvoice[{{ $loop->index }}][unit_price]" type="number" class="input" placeholder="Unit Price" value="{{ $proformaInvoiceDetail['unit_price'] ?? '' }}">
                                                <span class="icon is-small is-left">
                                                    <i class="fas fa-money-bill"></i>
                                                </span>
                                                @error('proformaInvoice.' . $loop->index . '.unit_price')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="control">
                                                <button id="proformaInvoice[{{ $loop->index }}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6 {{ userCompany()->isDiscountBeforeVAT() ? '' : 'is-hidden' }}">
                                        <label for="proformaInvoice[{{ $loop->index }}][discount]" class="label text-green has-text-weight-normal">Discount<sup class="has-text-danger"></sup> </label>
                                        <div class="field">
                                            <div class="control has-icons-left is-expanded">
                                                <input id="proformaInvoice[{{ $loop->index }}][discount]" name="proformaInvoice[{{ $loop->index }}][discount]" type="number" class="input" placeholder="Discount in Percentage" value="{{ $proformaInvoiceDetail['discount'] ?? '' }}">
                                                <span class="icon is-small is-left">
                                                    <i class="fas fa-percent"></i>
                                                </span>
                                                @error('proformaInvoice.' . $loop->index . '.discount')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12">
                                        <div class="field">
                                            <label for="proformaInvoice[{{ $loop->index }}][specification]" class="label text-green has-text-weight-normal">Specifications <sup class="has-text-danger"></sup> </label>
                                            <div class="control">
                                                <textarea name="proformaInvoice[{{ $loop->index }}][specification]" id="proformaInvoice[{{ $loop->index }}][specification]" cols="30" rows="5" class="summernote textarea"
                                                    placeholder="Specification about the product"> {{ $proformaInvoiceDetail['specification'] ?? '' }} </textarea>
                                                @error('proformaInvoice.' . $loop->index . '.specification')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button id="addNewProformaInvoiceForm" type="button" class="button bg-purple has-text-white is-small ml-3 mt-6">
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

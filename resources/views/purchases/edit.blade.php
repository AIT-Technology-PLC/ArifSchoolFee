@extends('layouts.app')

@section('title')
    Edit Purchase
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit Purchase
            </h1>
        </div>
        <form id="formOne" action="{{ route('purchases.update', $purchase->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="code" class="label text-green has-text-weight-normal">Purchase No <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="number" name="code" id="code" value="{{ $purchase->code }}">
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
                            <label for="purchased_on" class="label text-green has-text-weight-normal"> Purchase Date <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="purchased_on" id="purchased_on" placeholder="mm/dd/yyyy" value="{{ $purchase->purchased_on ? $purchase->purchased_on->toDateString() : '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                @error('purchased_on')
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
                                <input id="discount" name="discount" type="number" class="input" placeholder="Discount in Percentage" value="{{ $purchase->discount * 100 ?? '' }}">
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
                    <div class="column is-6">
                        <div class="field">
                            <label for="type" class="label text-green has-text-weight-normal">Purchase Type <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="type" name="type">
                                        <option selected disabled>Select Type</option>
                                        <option value="Local Purchase" {{ $purchase->isImported() ? '' : 'selected' }}>Local Purchase</option>
                                        <option value="Import" {{ $purchase->isImported() ? 'selected' : '' }}>Import</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                            </div>
                            @error('type')
                                <span class="help has-text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="payment_type" class="label text-green has-text-weight-normal">Payment Method <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="payment_type" name="payment_type">
                                        <option selected disabled>Select Payment</option>
                                        <option value="Cash Payment" {{ $purchase->payment_type == 'Cash Payment' ? 'selected' : '' }}>Cash Payment</option>
                                        <option value="Credit Payment" {{ $purchase->payment_type == 'Credit Payment' ? 'selected' : '' }}>Credit Payment</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                            </div>
                            @error('payment_type')
                                <span class="help has-text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="supplier_id" class="label text-green has-text-weight-normal"> Supplier <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="supplier_id" name="supplier_id">
                                        <option selected disabled>Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
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
                            <label for="description" class="label text-green has-text-weight-normal">Description</label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ $purchase->description ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-edit"></i>
                                </span>
                                @error('description')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($purchase->purchaseDetails as $purchaseDetail)
                    <div class="has-text-weight-medium has-text-left mt-5">
                        <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                            Item {{ $loop->index + 1 }} - {{ $purchaseDetail->product->name }}
                        </span>
                    </div>
                    <div class="box has-background-white-bis radius-top-0">
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label for="purchase[{{ $loop->index }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <x-product-list tags="false" name="purchase[{{ $loop->index }}]" selected-product-id="{{ $purchaseDetail->product_id }}" />
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-th"></i>
                                        </div>
                                        @error('purchase.{{ $loop->index }}.product_id')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label for="purchase[{{ $loop->index }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="purchase[{{ $loop->index }}][quantity]" name="purchase[{{ $loop->index }}][quantity]" type="number" class="input" placeholder="Purchase Quantity" value="{{ $purchaseDetail->quantity ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('purchase.{{ $loop->index }}.quantity')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button id="purchase[{{ $loop->index }}][product_id]Quantity" class="button bg-green has-text-white" type="button">
                                            {{ $purchaseDetail->product->unit_of_measurement }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label for="purchase[{{ $loop->index }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> ({{ userCompany()->getPriceMethod() }})</sup> <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="purchase[{{ $loop->index }}][unit_price]" name="purchase[{{ $loop->index }}][unit_price]" type="number" class="input" placeholder="Purchase Price" value="{{ $purchaseDetail->originalUnitPrice ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-money-bill"></i>
                                        </span>
                                        @error('purchase.{{ $loop->index }}.unit_price')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button id="purchase[{{ $loop->index }}][product_id]Price" class="button bg-green has-text-white" type="button">
                                            Per {{ $purchaseDetail->product->unit_of_measurement }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6 {{ userCompany()->isDiscountBeforeVAT() ? '' : 'is-hidden' }}">
                                <label for="purchase[{{ $loop->index }}][discount]" class="label text-green has-text-weight-normal">Discount <sup class="has-text-danger"></sup> </label>
                                <div class="field">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="purchase[{{ $loop->index }}][discount]" name="purchase[{{ $loop->index }}][discount]" type="number" class="input" placeholder="Discount in Percentage" value="{{ $purchaseDetail->discount * 100 ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-percent"></i>
                                        </span>
                                        @error('purchase.' . $loop->index . '.discount')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="box radius-top-0">
                <x-save-button />
            </div>
        </form>
    </section>
@endsection

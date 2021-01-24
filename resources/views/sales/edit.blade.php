@extends('layouts.app')

@section('title')
    Edit Sale
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit Sale
            </h1>
        </div>
        <form id="formOne" action="{{ route('sales.update', $sale->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="notification bg-gold has-text-white has-text-weight-medium {{ session('message') ? '' : 'is-hidden' }}">
                    <span class="icon">
                        <i class="fas fa-times-circle"></i>
                    </span>
                    <span>
                        {{ session('message') }}
                    </span>
                </div>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="receipt_no" class="label text-green has-text-weight-normal">Receipt No <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="receipt_no" id="receipt_no" value="{{ $sale->receipt_no ?? '' }}">
                                <span class="icon is-large is-left">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                @error('receipt_no')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="sold_on" class="label text-green has-text-weight-normal"> Sale Date <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="sold_on" id="sold_on" placeholder="mm/dd/yyyy" value="{{ $sale->sold_on ? $sale->sold_on->toDateString() : '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="payment_type" class="label text-green has-text-weight-normal">Payment Method <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="payment_type" name="payment_type">
                                        <option selected disabled>Select Payment</option>
                                        <option value="Cash Payment" {{ $sale->payment_type == 'Cash Payment' ? 'selected' : '' }}>Cash Payment</option>
                                        <option value="Credit Payment" {{ $sale->payment_type == 'Credit Payment' ? 'selected' : '' }}>Credit Payment</option>
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
                            <label for="customer_id" class="label text-green has-text-weight-normal"> Customer <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="customer_id" name="customer_id">
                                        <option selected disabled>Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->company_name }}</option>
                                        @endforeach
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
                            <label for="description" class="label text-green has-text-weight-normal">Additional Notes</label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ $sale->description ?? '' }}</textarea>
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
                @foreach ($sale->saleDetails as $saleDetail)
                    <div class="has-text-weight-medium has-text-left mt-5">
                        <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                            Item {{ $loop->index + 1 }} - {{ $saleDetail->product->name }}
                        </span>
                    </div>
                    <div class="box has-background-white-bis radius-top-0">
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label for="sale[{{ $loop->index }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <div class="select is-fullwidth">
                                            <select id="sale[{{ $loop->index }}][product_id]" name="sale[{{ $loop->index }}][product_id]" onchange="getProductSelected(this.id, this.value)">
                                                <option selected disabled>Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ $saleDetail->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-th"></i>
                                        </div>
                                        @error('sale.{{ $loop->index }}.product_id')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label for="sale[{{ $loop->index }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="sale[{{ $loop->index }}][quantity]" name="sale[{{ $loop->index }}][quantity]" type="number" class="input" placeholder="Sale Quantity" value="{{ $saleDetail->quantity ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('sale.{{ $loop->index }}.quantity')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button id="sale[{{ $loop->index }}][product_id]Quantity" class="button bg-green has-text-white" type="button">
                                            {{ $saleDetail->product->unit_of_measurement }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label for="sale[{{ $loop->index }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="sale[{{ $loop->index }}][unit_price]" name="sale[{{ $loop->index }}][unit_price]" type="number" class="input" placeholder="Sale Price" value="{{ $saleDetail->unit_price ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-money-bill"></i>
                                        </span>
                                        @error('sale.{{ $loop->index }}.unit_price')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button id="sale[{{ $loop->index }}][product_id]Price" class="button bg-green has-text-white" type="button">
                                            Per {{ $saleDetail->product->unit_of_measurement }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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

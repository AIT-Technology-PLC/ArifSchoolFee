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
        <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                @foreach ($purchase->purchaseDetails as $purchaseDetail)
                    <div class="mt-4">
                        <span class="py-4 px-2 has-background-white-ter text-purple has-text-weight-medium">
                            Item {{ $loop->index + 1 }} - {{ $purchaseDetail->product->name }}
                        </span>
                    </div>
                    <div class="columns is-marginless is-multiline has-background-white-ter mb-5">
                        <div class="column is-12">
                            <div class="field">
                                <label for="purchase[{{ $loop->index }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select id="purchase[{{ $loop->index }}][product_id]" name="purchase[{{ $loop->index }}][product_id]">
                                            <option selected disabled>Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" {{ $purchaseDetail->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                            <option value="" {{ $purchaseDetail->product_id == '' ? 'selected' : '' }}>None</option>
                                        </select>
                                    </div>
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
                                    <button class="button bg-green has-text-white" type="button">
                                        {{ $purchaseDetail->product->unit_of_measurement }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="purchase[{{ $loop->index }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="purchase[{{ $loop->index }}][unit_price]" name="purchase[{{ $loop->index }}][unit_price]" type="number" class="input" placeholder="Purchase Price" value="{{ $purchaseDetail->unit_price ?? '' }}">
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
                                    <button class="button bg-green has-text-white" type="button">
                                        {{ $purchaseDetail->product->unit_of_measurement }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <hr>
                <div class="columns is-marginless is-multiline">
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
                                        <option value="" {{ $purchase->supplier_id == '' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-address-card"></i>
                                </div>
                                @error('supplier_id')
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
                                <input class="input" type="date" name="purchased_on" id="purchased_on" value="{{ $purchase->purchased_on ? $purchase->purchased_on->toDateString() : '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="shipping_line" class="label text-green has-text-weight-normal"> Shipping Line <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="shipping_line" name="shipping_line">
                                        <option selected disabled>Select Line</option>
                                        @foreach ($shippingLines as $shippingLine)
                                            <option value="{{ $shippingLine }}" {{ $purchase->shipping_line == $shippingLine ? 'selected' : '' }}>{{ $shippingLine }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-truck"></i>
                                </div>
                                @error('shipping_line')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @if (!$purchase->isAddedToInventory())
                        <div class="column is-6">
                            <div class="field">
                                <label for="status" class="label text-green has-text-weight-normal"> Purchase Status <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select id="status" name="status">
                                            <option selected disabled>Select Status</option>
                                            @foreach ($purchaseStatuses as $purchaseStatus)
                                                <option value="{{ $purchaseStatus }}" {{ $purchase->status == $purchaseStatus ? 'selected' : '' }}>{{ $purchaseStatus }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    @error('status')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="column is-6">
                        <div class="field">
                            <label for="shipped_at" class="label text-green has-text-weight-normal"> Shipping Started On <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="shipped_at" id="shipped_at" value="{{ $purchase->shipped_at ? $purchase->shipped_at->toDateString() : '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="delivered_at" class="label text-green has-text-weight-normal"> Delivered To You On <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="delivered_at" id="delivered_at" value="{{ $purchase->delivered_at ? $purchase->delivered_at->toDateString() : '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="description" class="label text-green has-text-weight-normal">Additional Notes</label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="10" class="textarea pl-6" placeholder="Description or note to be taken">{{ $purchase->description ?? '' }}</textarea>
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
                            <button class="button bg-green has-text-white">
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

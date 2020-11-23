@extends('layouts.app')

@section('title')
    Edit Purchase - {{ $purchase->product->name }}
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit Purchase - {{ $purchase->product->name }}
            </h1>
        </div>
        <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="product_id" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="product_id" name="product_id">
                                        <option selected disabled>Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ $purchase->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-th"></i>
                                </div>
                                @error('product_id')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="supplier_id" class="label text-green has-text-weight-normal"> Supplier <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="supplier_id" name="supplier_id">
                                        <option selected disabled>Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                                        @endforeach
                                        <option value="" {{ is_null($purchase->supplier_id) ? 'selected' : '' }}>None</option>
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
                            <label for="total_quantity" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="total_quantity" name="total_quantity" type="number" class="input" placeholder="Purchase Quantity" value="{{ $purchase->total_quantity ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                                @error('total_quantity')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="total_price" class="label text-green has-text-weight-normal">Price <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="total_price" name="total_price" type="number" class="input" placeholder="Purchase Price" value="{{ $purchase->total_price ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                                @error('total_price')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
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
                                        <option value="DHL" {{ $purchase->shipping_line == 'DHL' ? 'selected' : '' }}>DHL</option>
                                        <option value="CMG" {{ $purchase->shipping_line == 'CMG' ? 'selected' : '' }}>CMG</option>
                                        <option value="Other" {{ $purchase->shipping_line == 'Other' ? 'selected' : '' }}>Other</option>
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
                    <div class="column is-6">
                        <div class="field">
                            <label for="payment_status" class="label text-green has-text-weight-normal"> Payment Status <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="payment_status" name="payment_status">
                                        <option selected disabled>Select Status</option>
                                        <option value="Full" {{ $purchase->payment_status == 'Full' ? 'selected' : '' }}>Full</option>
                                        <option value="Partial" {{ $purchase->payment_status == 'Partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="None" {{ $purchase->payment_status == 'None' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                @error('payment_status')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
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

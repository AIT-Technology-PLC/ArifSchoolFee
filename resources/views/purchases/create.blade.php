@extends('layouts.app')

@section('title')
    Create New Purchase
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Purchase
            </h1>
        </div>
        <form action="{{ route('purchases.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="product_id" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="product_id" name="purchase[product_id][]">
                                        <option selected disabled>Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ old('purchase.product_id.0') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                        <option value="" {{ old('purchase.product_id.0') == '' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-th"></i>
                                </div>
                                @error('purchase.product_id.0')
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
                                    <select id="supplier_id" name="purchase[supplier_id][]">
                                        <option selected disabled>Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('purchase.supplier_id.0') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                                        @endforeach
                                        <option value="" {{ old('purchase.supplier_id.0') == '' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-address-card"></i>
                                </div>
                                @error('purchase.supplier_id.0')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="quantity" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="quantity" name="purchase[quantity][]" type="number" class="input" placeholder="Purchase Quantity" value="{{ old('purchase.quantity.0') ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                                @error('purchase.quantity.0')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="unit_price" class="label text-green has-text-weight-normal">Price <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="unit_price" name="purchase[unit_price][]" type="number" class="input" placeholder="Purchase Price" value="{{ old('purchase.unit_price.0') ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                                @error('purchase.unit_price.0')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @for ($i = 1; $i < 10; $i++)
                    @if (old('purchase.product_id.' . $i) || old('purchase.supplier_id.' . $i) || old('purchase.quantity.' . $i) || old('purchase.unit_price.' . $i))
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label for="product_id" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <div class="select is-fullwidth">
                                            <select id="product_id" name="purchase[product_id][]">
                                                <option selected disabled>Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ old('purchase.product_id.' . $i) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                @endforeach
                                                <option value="" {{ old('purchase.product_id.' . $i) == '' ? 'selected' : '' }}>None</option>
                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-th"></i>
                                        </div>
                                        @error('purchase.product_id.' . $i)
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
                                            <select id="supplier_id" name="purchase[supplier_id][]">
                                                <option selected disabled>Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" {{ old('purchase.supplier_id.' . $i) == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                                                @endforeach
                                                <option value="" {{ old('purchase.supplier_id.' . $i) == '' ? 'selected' : '' }}>None</option>
                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-address-card"></i>
                                        </div>
                                        @error('purchase.supplier_id.' . $i)
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="quantity" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <input id="quantity" name="purchase[quantity][]" type="number" class="input" placeholder="Purchase Quantity" value="{{ old('purchase.quantity.' . $i) ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('purchase.quantity.' . $i)
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="unit_price" class="label text-green has-text-weight-normal">Price <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <input id="unit_price" name="purchase[unit_price][]" type="number" class="input" placeholder="Purchase Price" value="{{ old('purchase.unit_price.' . $i) ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-money-bill"></i>
                                        </span>
                                        @error('purchase.unit_price.' . $i)
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @break
                    @endif
                @endfor
                <hr>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="shipping_line" class="label text-green has-text-weight-normal"> Shipping Line <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="shipping_line" name="shipping_line">
                                        <option selected disabled>Select Line</option>
                                        <option value="DHL" {{ old('shipping_line') == 'DHL' ? 'selected' : '' }}>DHL</option>
                                        <option value="CMG" {{ old('shipping_line') == 'CMG' ? 'selected' : '' }}>CMG</option>
                                        <option value="Other" {{ old('shipping_line') == 'Other' ? 'selected' : '' }}>Other</option>
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
                            <label for="status" class="label text-green has-text-weight-normal"> Purchase Status <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="status" name="status">
                                        <option selected disabled>Select Status</option>
                                        <option value="Quotation" {{ old('status') == 'Quotation' ? 'selected' : '' }}>Quotation</option>
                                        <option value="Confirmed Order" {{ old('status') == 'Confirmed Order' ? 'selected' : '' }}>Confirmed Order</option>
                                        <option value="Shipped" {{ old('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="Delivered" {{ old('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="Added to Inventory" {{ old('status') == 'Added to Inventory' ? 'selected' : '' }}>Added to Inventory</option>
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
                    <div class="column is-6">
                        <div class="field">
                            <label for="shipped_at" class="label text-green has-text-weight-normal"> Shipping Started On <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="shipped_at" id="shipped_at">
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
                                <input class="input" type="date" name="delivered_at" id="delivered_at">
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
                                <textarea name="description" id="description" cols="30" rows="10" class="textarea pl-6" placeholder="Description or note to be taken">{{ old('description') ?? '' }}</textarea>
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

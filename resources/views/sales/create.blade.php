@extends('layouts.app')

@section('title')
    Create New Sale
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Sales
            </h1>
        </div>
        <form id="formOne" action="{{ route('sales.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="notification bg-gold has-text-white has-text-weight-medium {{ session('message') ? '' : 'is-hidden' }}">
                    <span class="icon">
                        <i class="fas fa-times-circle"></i>
                    </span>
                    <span>
                        {{ session('message') }}
                    </span>
                </div>
                <div class="mt-4">
                    <span class="py-4 px-2 has-background-white-ter text-purple has-text-weight-medium">
                        Item 1
                    </span>
                </div>
                <div name="saleFormGroup" class="columns is-marginless is-multiline has-background-white-ter mb-5">
                    <div class="column is-12">
                        <div class="field">
                            <label for="sale[0][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="sale[0][product_id]" name="sale[0][product_id]" onchange="getProductSelected(this.id, this.value)">
                                        <option selected disabled>Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ old('sale.0.product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-th"></i>
                                </div>
                                @error('sale.0.product_id')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <label for="sale[0][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="sale[0][quantity]" name="sale[0][quantity]" type="number" class="input" placeholder="Sale Quantity" value="{{ old('sale.0.quantity') ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                                @error('sale.0.quantity')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="control">
                                <button id="sale[0][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <label for="sale[0][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="sale[0][unit_price]" name="sale[0][unit_price]" type="number" class="input" placeholder="Sale Price" value="{{ old('sale.0.unit_price') ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                                @error('sale.0.unit_price')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="control">
                                <button id="sale[0][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                </div>
                @for ($i = 1; $i < 10; $i++)
                    @if (old('sale.' . $i . '.product_id') || old('sale.' . $i . '.customer_id') || old('sale.' . $i . '.quantity') || old('sale.' . $i . '.unit_price'))
                        <div class="mt-4">
                            <span class="py-4 px-2 has-background-white-ter text-purple has-text-weight-medium">
                                Item {{ $i + 1 }}
                            </span>
                        </div>
                        <div name="saleFormGroup" class="columns is-marginless is-multiline has-background-white-ter mb-5">
                            <div class="column is-12">
                                <div class="field">
                                    <label for="sale[{{ $i }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <div class="select is-fullwidth">
                                            <select id="sale[{{ $i }}][product_id]" name="sale[{{ $i }}][product_id]" onchange="getProductSelected(this.id, this.value)">
                                                <option selected disabled>Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ old('sale.' . $i . '.product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-th"></i>
                                        </div>
                                        @error('sale.' . $i . '.product_id')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label for="sale[{{ $i }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="sale[{{ $i }}][quantity]" name="sale[{{ $i }}][quantity]" type="number" class="input" placeholder="Sale Quantity" value="{{ old('sale.' . $i . '.quantity') ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('sale.' . $i . '.quantity')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button id="sale[{{ $i }}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label for="sale[{{ $i }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="sale[{{ $i }}][unit_price]" name="sale[{{ $i }}][unit_price]" type="number" class="input" placeholder="Sale Price" value="{{ old('sale.' . $i . '.unit_price') ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-money-bill"></i>
                                        </span>
                                        @error('sale.' . $i . '.unit_price')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button id="sale[{{ $i }}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @break
                    @endif
                @endfor
                <div id="saleFormWrapper"></div>
                <button id="addNewSaleForm" type="button" class="button bg-purple has-text-white is-small ml-3 mt-3">
                    Add More Sale
                </button>
                <hr>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="status" class="label text-green has-text-weight-normal"> Sale Status <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input type="radio" name="status" id="status" value="Subtracted From Inventory" {{ old('status') == 'Subtracted From Inventory' ? 'checked' : '' }} checked>
                                    Yes, subtract now.
                                </label>
                                <br>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input type="radio" name="status" id="status" value="Not Subtracted From Inventory" {{ old('status') == 'Not Subtracted From Inventory' ? 'checked' : '' }}>
                                    No, subtract later.
                                </label>
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
                            <label for="sold_on" class="label text-green has-text-weight-normal"> Sold On <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="sold_on" id="sold_on" placeholder="mm/dd/yyyy" value="{{ old('sold_on') ?? '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
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
                            <label for="shipping_line" class="label text-green has-text-weight-normal"> Shipping Line <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="shipping_line" name="shipping_line">
                                        <option selected disabled>Select Line</option>
                                        @foreach ($shippingLines as $shippingLine)
                                            <option value="{{ $shippingLine }}" {{ old('shipping_line') == $shippingLine ? 'selected' : '' }}>{{ $shippingLine }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-truck"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="shipped_at" class="label text-green has-text-weight-normal"> Shipping Started On <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="shipped_at" id="shipped_at" placeholder="mm/dd/yyyy" value="{{ old('shipped_at') ?? '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="delivered_at" class="label text-green has-text-weight-normal"> Delivered To Customer On <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="delivered_at" id="delivered_at" placeholder="mm/dd/yyyy" value="{{ old('delivered_at') ?? '' }}">
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

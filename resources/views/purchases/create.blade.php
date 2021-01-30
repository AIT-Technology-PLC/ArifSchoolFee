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
        <form id="formOne" action="{{ route('purchases.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="has-text-weight-medium has-text-left">
                    <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                        Item 1
                    </span>
                </div>
                <div class="box has-background-white-bis radius-top-0">
                    <div name="purchaseFormGroup" class="columns is-marginless is-multiline">
                        <div class="column is-12">
                            <div class="field">
                                <label for="purchase[0][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select id="purchase[0][product_id]" name="purchase[0][product_id]" onchange="getProductSelected(this.id, this.value)">
                                            <option selected disabled>Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" {{ old('purchase.0.product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-th"></i>
                                    </div>
                                    @error('purchase.0.product_id')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="purchase[0][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="purchase[0][quantity]" name="purchase[0][quantity]" type="number" class="input" placeholder="Purchase Quantity" value="{{ old('purchase.0.quantity') ?? '' }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-balance-scale"></i>
                                    </span>
                                    @error('purchase.0.quantity')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="control">
                                    <button id="purchase[0][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="purchase[0][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="purchase[0][unit_price]" name="purchase[0][unit_price]" type="number" class="input" placeholder="Purchase Price" value="{{ old('purchase.0.unit_price') ?? '' }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-money-bill"></i>
                                    </span>
                                    @error('purchase.0.unit_price')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="control">
                                    <button id="purchase[0][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @for ($i = 1; $i < 10; $i++)
                    @if (old('purchase.' . $i . '.product_id') || old('purchase.' . $i . '.supplier_id') || old('purchase.' . $i . '.quantity') || old('purchase.' . $i . '.unit_price'))
                        <div class="has-text-weight-medium has-text-left">
                            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                                Item {{ $i + 1 }}
                            </span>
                        </div>
                        <div class="box has-background-white-bis radius-top-0">
                            <div name="purchaseFormGroup" class="columns is-marginless is-multiline">
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="purchase[{{ $i }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                        <div class="control has-icons-left">
                                            <div class="select is-fullwidth">
                                                <select id="purchase[{{ $i }}][product_id]" name="purchase[{{ $i }}][product_id]" onchange="getProductSelected(this.id, this.value)">
                                                    <option selected disabled>Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ old('purchase.' . $i . '.product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="icon is-small is-left">
                                                <i class="fas fa-th"></i>
                                            </div>
                                            @error('purchase.' . $i . '.product_id')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="purchase[{{ $i }}][supplier_id]" class="label text-green has-text-weight-normal"> Supplier <sup class="has-text-danger">*</sup> </label>
                                        <div class="control has-icons-left">
                                            <div class="select is-fullwidth">
                                                <select id="purchase[{{ $i }}][supplier_id]" name="purchase[{{ $i }}][supplier_id]" onchange="getProductSelected(this.id, this.value)">
                                                    <option selected disabled>Select Supplier</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}" {{ old('purchase.' . $i . '.supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                                                    @endforeach
                                                    <option value="" {{ old('purchase.' . $i . '.supplier_id') == '' ? 'selected' : '' }}>None</option>
                                                </select>
                                            </div>
                                            <div class="icon is-small is-left">
                                                <i class="fas fa-address-card"></i>
                                            </div>
                                            @error('purchase.' . $i . '.supplier_id')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <label for="purchase[{{ $i }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                    <div class="field has-addons">
                                        <div class="control has-icons-left is-expanded">
                                            <input id="purchase[{{ $i }}][quantity]" name="purchase[{{ $i }}][quantity]" type="number" class="input" placeholder="Purchase Quantity" value="{{ old('purchase.' . $i . '.quantity') ?? '' }}">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-balance-scale"></i>
                                            </span>
                                            @error('purchase.' . $i . '.quantity')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="control">
                                            <button id="purchase[{{ $i }}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <label for="purchase[{{ $i }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                                    <div class="field has-addons">
                                        <div class="control has-icons-left is-expanded">
                                            <input id="purchase[{{ $i }}][unit_price]" name="purchase[{{ $i }}][unit_price]" type="number" class="input" placeholder="Purchase Price" value="{{ old('purchase.' . $i . '.unit_price') ?? '' }}">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-money-bill"></i>
                                            </span>
                                            @error('purchase.' . $i . '.unit_price')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="control">
                                            <button id="purchase[{{ $i }}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @break
                    @endif
                @endfor
                <div id="purchaseFormWrapper"></div>
                <button id="addNewPurchaseForm" type="button" class="button bg-purple has-text-white is-small ml-3 mt-3">
                    Add More Purchase
                </button>
                <hr>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="status" class="label text-green has-text-weight-normal"> Add to inventory now? <sup class="has-text-danger">*</sup> </label>
                            <div class="control">
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input type="radio" name="status" id="status" value="Added To Inventory" {{ old('status') == 'Added To Inventory' ? 'checked' : '' }} checked>
                                    Yes, add now.
                                </label>
                                <br>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input type="radio" name="status" id="status" value="Not Added To Inventory" {{ old('status') == 'Not Added To Inventory' ? 'checked' : '' }}>
                                    No, add later.
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
                            <label for="purchased_on" class="label text-green has-text-weight-normal"> Purchase Date <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="purchased_on" id="purchased_on" placeholder="mm/dd/yyyy" value="{{ old('purchased_on') ?? '' }}">
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
                    <div class="column is-6">
                        <div class="field">
                            <label for="warehouse_id" class="label text-green has-text-weight-normal"> Warehouse <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="warehouse_id" name="warehouse_id">
                                        <option selected disabled>Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                                @error('warehouse_id')
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
                                <input class="input" type="date" name="expires_on" id="expires_on" value="{{ old('expires_on') ?? '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                            </div>
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
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-address-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
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

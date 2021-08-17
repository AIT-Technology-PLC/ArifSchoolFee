@extends('layouts.app')

@section('title')
    Create New DO/GDN
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New DO/GDN
            </h1>
        </div>
        <form id="formOne" action="{{ route('gdns.store')}}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="code" class="label text-green has-text-weight-normal">DO/GDN Number <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="number" name="code" id="code" value="{{ $currentGdnCode + 1 }}">
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
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($enabledFeatures->contains('Sale Management'))                        
                        <div class="column is-6">
                            <div class="field">
                                <label for="sale_id" class="label text-green has-text-weight-normal"> Receipt No <sup class="has-text-danger"></sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select id="sale_id" name="sale_id">
                                            <option selected disabled>Select Sale</option>
                                            @foreach ($sales as $sale)
                                                <option value="{{ $sale->id }}" {{ old('sale_id') == $sale->id ? 'selected' : '' }}>{{ $sale->receipt_no ?? '' }}</option>
                                            @endforeach
                                            <option value="">None</option>
                                        </select>
                                    </div>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
                            <label for="payment_type" class="label text-green has-text-weight-normal">Payment Method <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="payment_type" name="payment_type">
                                        <option selected disabled>Select Payment</option>
                                        <option value="Cash Payment" {{ old('payment_type') == 'Cash Payment' ? 'selected' : '' }}>Cash Payment</option>
                                        <option value="Credit Payment" {{ old('payment_type') == 'Credit Payment' ? 'selected' : '' }}>Credit Payment</option>
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
                        <label for="cash_received_in_percentage" class="label text-green has-text-weight-normal">Cash Received <sup class="has-text-danger">*</sup> <sup class="has-text-weight-light"> (In Percentage)</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input class="input" type="number" name="cash_received_in_percentage" id="cash_received_in_percentage" placeholder="eg. 50" value="{{ old('cash_received_in_percentage') ?? '' }}">
                                <span class="icon is-large is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                                @error('cash_received_in_percentage')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="control">
                                <button class="button bg-green has-text-white" type="button">%</button>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="description" class="label text-green has-text-weight-normal"> Description <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ old('description') ?? '' }}</textarea>
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
                <div class="has-text-weight-medium has-text-left mt-5">
                    <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                        Item 1
                    </span>
                </div>
                <div class="box has-background-white-bis radius-top-0">
                    <div name="gdnFormGroup" class="columns is-marginless is-multiline">
                        <div class="column is-6">
                            <div class="field">
                                <label for="gdn[0][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <x-product-list model="gdn" selected-product_id="{{ old('gdn.0.product_id') }}"/>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-th"></i>
                                    </div>
                                    @error('gdn.0.product_id')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label for="gdn[0][warehouse_id]" class="label text-green has-text-weight-normal"> From <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select id="gdn[0][warehouse_id]" name="gdn[0][warehouse_id]">
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ old('gdn.0.warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                    @error('gdn.0.warehouse_id')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="gdn[0][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="gdn[0][quantity]" name="gdn[0][quantity]" type="number" class="input" placeholder="Quantity" value="{{ old('gdn.0.quantity') ?? '' }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-balance-scale"></i>
                                    </span>
                                    @error('gdn.0.quantity')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="control">
                                    <button id="gdn[0][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="gdn[0][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <sup class="has-text-danger"></sup> </label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="gdn[0][unit_price]" name="gdn[0][unit_price]" type="number" class="input" placeholder="Sale Price" value="{{ old('gdn.0.unit_price') ?? '0.00' }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-money-bill"></i>
                                    </span>
                                    @error('gdn.0.unit_price')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="control">
                                    <button id="gdn[0][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label for="gdn[0][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                                <div class="control has-icons-left">
                                    <textarea name="gdn[0][description]" id="gdn[0][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ old('gdn.0.description') ?? '' }}</textarea>
                                    <span class="icon is-large is-left">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    @error('gdn.0.description')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @for ($i = 1; $i < 10; $i++)
                    @if (old('gdn.' . $i . '.product_id') || old('gdn.' . $i . '.quantity'))
                        <div class="has-text-weight-medium has-text-left">
                            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                                Item {{ $i + 1 }}
                            </span>
                        </div>
                        <div class="box has-background-white-bis radius-top-0">
                            <div name="gdnFormGroup" class="columns is-marginless is-multiline">
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="gdn[{{ $i }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                        <div class="control has-icons-left">
                                            <x-product-list model="gdn" selected-product_id="{{ old('gdn.' . $i . '.product_id') }}"/>
                                            <div class="icon is-small is-left">
                                                <i class="fas fa-th"></i>
                                            </div>
                                            @error('gdn.' . $i . '.product_id')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="gdn[{{ $i }}][warehouse_id]" class="label text-green has-text-weight-normal"> From <sup class="has-text-danger">*</sup> </label>
                                        <div class="control has-icons-left">
                                            <div class="select is-fullwidth">
                                                <select id="gdn[{{ $i }}][warehouse_id]" name="gdn[{{ $i }}][warehouse_id]">
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}" {{ old('gdn.' . $i . '.warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="icon is-small is-left">
                                                <i class="fas fa-warehouse"></i>
                                            </div>
                                            @error('gdn.' . $i . '.warehouse_id')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <label for="gdn[{{ $i }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                    <div class="field has-addons">
                                        <div class="control has-icons-left is-expanded">
                                            <input id="gdn[{{ $i }}][quantity]" name="gdn[{{ $i }}][quantity]" type="number" class="input" placeholder="Quantity" value="{{ old('gdn.' . $i . '.quantity') ?? '' }}">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-balance-scale"></i>
                                            </span>
                                            @error('gdn.' . $i . '.quantity')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="control">
                                            <button id="gdn[{{ $i }}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <label for="gdn[{{ $i }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <unit_price class="has-text-danger"></sup> </label>
                                    <div class="field has-addons">
                                        <div class="control has-icons-left is-expanded">
                                            <input id="gdn[{{ $i }}][unit_price]" name="gdn[{{ $i }}][unit_price]" type="number" class="input" placeholder="Unit Price" value="{{ old('gdn.' . $i . '.unit_price') ?? '0.00' }}">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-money-bill"></i>
                                            </span>
                                            @error('gdn.' . $i . '.unit_price')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="control">
                                            <button id="gdn[{{ $i }}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="gdn[{{ $i }}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                                        <div class="control has-icons-left">
                                            <textarea name="gdn[{{ $i }}][description]" id="gdn[{{ $i }}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ old('gdn.' . $i . '.description') ?? '' }}</textarea>
                                            <span class="icon is-large is-left">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                            @error('gdn.' . $i . '.description')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @break
                    @endif
                @endfor
                <div id="gdnFormWrapper"></div>
                <button id="addNewGdnForm" type="button" class="button bg-purple has-text-white is-small ml-3 mt-3">
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
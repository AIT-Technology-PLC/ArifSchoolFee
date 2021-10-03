@extends('layouts.app')

@section('title')
    Create New Delivery Order
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Delivery Order
            </h1>
        </div>
        <form id="formOne" action="{{ route('gdns.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="code" class="label text-green has-text-weight-normal">DO Number <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="number" name="code" id="code" value="{{ $currentGdnCode }}">
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
                    @if (isFeatureEnabled('Sale Management'))
                        <div class="column is-6">
                            <div class="field">
                                <label for="sale_id" class="label text-green has-text-weight-normal"> Receipt No <sup class="has-text-danger"></sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select id="sale_id" name="sale_id">
                                            <option selected disabled>Select Sale</option>
                                            @foreach ($sales as $sale)
                                                <option value="{{ $sale->id }}" {{ old('sale_id') == $sale->id ? 'selected' : '' }}>{{ $sale->code ?? '' }}</option>
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
                    <div class="column is-12">
                        <div class="field">
                            <label for="description" class="label text-green has-text-weight-normal"> Description <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="3" class="summernote textarea" placeholder="Description or note to be taken">{{ old('description') ?? '' }}</textarea>
                                @error('description')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div id="gdn-details">
                    @foreach (old('gdn', [0]) as $gdnDetail)
                        <div class="gdn-detail mx-3">
                            <div class="has-text-weight-medium has-text-left mt-5">
                                <span name="item-number" class="tag bg-green has-text-white is-medium radius-bottom-0">
                                    Item {{ $loop->iteration }}
                                </span>
                            </div>
                            <div class="box has-background-white-bis radius-top-0">
                                <div name="gdnFormGroup" class="columns is-marginless is-multiline">
                                    <div class="column is-6">
                                        <div class="field">
                                            <label for="gdn[{{ $loop->index }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                            <div class="control has-icons-left">
                                                <x-product-list tags="false" name="gdn[{{ $loop->index }}]" selected-product-id="{{ $gdnDetail['product_id'] ?? '' }}" />
                                                <div class="icon is-small is-left">
                                                    <i class="fas fa-th"></i>
                                                </div>
                                                @error('gdn.' . $loop->index . '.product_id')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <div class="field">
                                            <label for="gdn[{{ $loop->index }}][warehouse_id]" class="label text-green has-text-weight-normal"> From <sup class="has-text-danger">*</sup> </label>
                                            <div class="control has-icons-left">
                                                <div class="select is-fullwidth">
                                                    <select id="gdn[{{ $loop->index }}][warehouse_id]" name="gdn[{{ $loop->index }}][warehouse_id]">
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}" {{ ($gdnDetail['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="icon is-small is-left">
                                                    <i class="fas fa-warehouse"></i>
                                                </div>
                                                @error('gdn.' . $loop->index . '.warehouse_id')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <label for="gdn[{{ $loop->index }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                        <div class="field has-addons">
                                            <div class="control has-icons-left is-expanded">
                                                <input id="gdn[{{ $loop->index }}][quantity]" name="gdn[{{ $loop->index }}][quantity]" type="number" class="input" placeholder="Quantity" value="{{ $gdnDetail['quantity'] ?? '' }}">
                                                <span class="icon is-small is-left">
                                                    <i class="fas fa-balance-scale"></i>
                                                </span>
                                                @error('gdn.' . $loop->index . '.quantity')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="control">
                                                <button id="gdn[{{ $loop->index }}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <label for="gdn[{{ $loop->index }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> ({{ userCompany()->getPriceMethod() }})</sup></label>
                                        <div class="field has-addons">
                                            <div class="control has-icons-left is-expanded">
                                                <input id="gdn[{{ $loop->index }}][unit_price]" name="gdn[{{ $loop->index }}][unit_price]" type="number" class="input" placeholder="Unit Price" value="{{ $gdnDetail['unit_price'] ?? '0.00' }}">
                                                <span class="icon is-small is-left">
                                                    <i class="fas fa-money-bill"></i>
                                                </span>
                                                @error('gdn.' . $loop->index . '.unit_price')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="control">
                                                <button id="gdn[{{ $loop->index }}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6 {{ userCompany()->isDiscountBeforeVAT() ? '' : 'is-hidden' }}">
                                        <label for="gdn[{{ $loop->index }}][discount]" class="label text-green has-text-weight-normal">Discount <sup class="has-text-danger"></sup> </label>
                                        <div class="field">
                                            <div class="control has-icons-left is-expanded">
                                                <input id="gdn[{{ $loop->index }}][discount]" name="gdn[{{ $loop->index }}][discount]" type="number" class="input" placeholder="Discount in Percentage" value="{{ $gdnDetail['discount'] ?? '' }}">
                                                <span class="icon is-small is-left">
                                                    <i class="fas fa-percent"></i>
                                                </span>
                                                @error('gdn.' . $loop->index . '.discount')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <div class="field">
                                            <label for="gdn[{{ $loop->index }}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                                            <div class="control has-icons-left">
                                                <textarea name="gdn[{{ $loop->index }}][description]" id="gdn[{{ $loop->index }}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ $gdnDetail['description'] ?? '' }}</textarea>
                                                <span class="icon is-large is-left">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                @error('gdn.' . $loop->index . '.description')
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
                <button id="addNewGdnForm" type="button" class="button bg-purple has-text-white is-small ml-3 mt-6">
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

@extends('layouts.app')

@section('title')
    Edit Purchase Order
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit Purchase Order
            </h1>
        </div>
        <form id="formOne" action="{{ route('purchase-orders.update', $purchaseOrder->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="code" class="label text-green has-text-weight-normal">Purchase Order No <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="code" id="code" value="{{ $purchaseOrder->code }}">
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
                                            <option value="{{ $customer->id }}" {{ $purchaseOrder->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->company_name }}</option>
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
                            <label for="description" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ $purchaseOrder->description ?? '' }}</textarea>
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
                @foreach ($purchaseOrder->purchaseOrderDetails as $purchaseOrderDetail)
                    <div class="has-text-weight-medium has-text-left mt-5">
                        <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                            Item {{ $loop->index + 1 }}
                        </span>
                    </div>
                    <div class="box has-background-white-bis radius-top-0">
                        <div name="purchaseOrderFormGroup" class="columns is-marginless is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label for="purchaseOrder[{{ $loop->index }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <div class="select is-fullwidth">
                                            <select id="purchaseOrder[{{ $loop->index }}][product_id]" name="purchaseOrder[{{ $loop->index }}][product_id]" onchange="getProductSelected(this.id, this.value)">
                                                <option selected disabled>Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ $purchaseOrderDetail->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-th"></i>
                                        </div>
                                        @error('purchaseOrder.' . $loop->index . '.product_id')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label for="purchaseOrder[{{ $loop->index }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="purchaseOrder[{{ $loop->index }}][quantity]" name="purchaseOrder[{{ $loop->index }}][quantity]" type="number" class="input" placeholder="Quantity" value="{{ $purchaseOrderDetail->quantity ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('purchaseOrder.' . $loop->index . '.quantity')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button id="purchaseOrder[{{ $loop->index }}][product_id]Quantity" class="button bg-green has-text-white" type="button">{{ $purchaseOrderDetail->product->unit_of_measurement }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label for="purchaseOrder[{{ $loop->index }}][quantity_left]" class="label text-green has-text-weight-normal">Quantity Remaining <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="purchaseOrder[{{ $loop->index }}][quantity_left]" name="purchaseOrder[{{ $loop->index }}][quantity_left]" type="number" class="input" placeholder="Quantity" value="{{ $purchaseOrderDetail->quantity_left ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('purchaseOrder.' . $loop->index . '.quantity_left')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button id="purchaseOrder[{{ $loop->index }}][product_id]Quantity" class="button bg-green has-text-white" type="button">{{ $purchaseOrderDetail->product->unit_of_measurement }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label for="purchaseOrder[{{ $loop->index }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="purchaseOrder[{{ $loop->index }}][unit_price]" name="purchaseOrder[{{ $loop->index }}][unit_price]" type="number" class="input" placeholder="Unit Price" value="{{ $purchaseOrderDetail->unit_price }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('purchaseOrder.' . $loop->index . '.unit_price')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button id="purchaseOrder[{{ $loop->index }}][product_id]Price" class="button bg-green has-text-white" type="button">{{ $purchaseOrderDetail->product->unit_of_measurement }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="purchaseOrder[{{ $loop->index }}][description]" class="label text-green has-text-weight-normal">Description <sup class="has-text-danger"></sup></label>
                                    <div class="control has-icons-left">
                                        <textarea name="purchaseOrder[{{ $loop->index }}][description]" id="purchaseOrder[{{ $loop->index }}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ $purchaseOrderDetail->description ?? '' }}</textarea>
                                        <span class="icon is-large is-left">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        @error('purchaseOrder.' . $loop->index . '.description')
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

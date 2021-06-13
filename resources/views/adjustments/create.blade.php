@extends('layouts.app')

@section('title')
    Create New Adjustment
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Adjustment
            </h1>
        </div>
        <form id="formOne" action="{{ route('adjustments.store')}}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="code" class="label text-green has-text-weight-normal">Adjustment Number <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="number" name="code" id="code" value="{{ $currentAdjustmentCode + 1 }}">
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
                            <label for="reason" class="label text-green has-text-weight-normal">Description <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea name="reason" id="reason" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ old('reason') ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-edit"></i>
                                </span>
                                @error('reason')
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
                    <div name="adjustmentFormGroup" class="columns is-marginless is-multiline">
                        <div class="column is-6">
                            <div class="field">
                                <label for="adjustment[0][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select id="adjustment[0][product_id]" name="adjustment[0][product_id]" onchange="getProductSelected(this.id, this.value)">
                                            <option selected disabled>Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" {{ old('adjustment.0.product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-th"></i>
                                    </div>
                                    @error('adjustment.0.product_id')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="adjustment[0][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="adjustment[0][quantity]" name="adjustment[0][quantity]" type="number" class="input" placeholder="Quantity" value="{{ old('adjustment.0.quantity') ?? '' }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-balance-scale"></i>
                                    </span>
                                    @error('adjustment.0.quantity')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="control">
                                    <button id="adjustment[0][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label for="adjustment[0][is_subtract]" class="label text-green has-text-weight-normal"> Type <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select id="adjustment[0][is_subtract]" name="adjustment[0][is_subtract]">
                                            <option value="0" {{ old('adjustment.0.is_subtract') == 0 ? 'selected' : '' }}> Add </option>
                                            <option value="1" {{ old('adjustment.0.is_subtract') == 1 ? 'selected' : '' }}> Subtract </option>
                                        </select>
                                    </div>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-sort"></i>
                                    </div>
                                    @error('adjustment.0.is_subtract')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label for="adjustment[0][warehouse_id]" class="label text-green has-text-weight-normal"> Warehouse <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select id="adjustment[0][warehouse_id]" name="adjustment[0][warehouse_id]">
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ old('adjustment.0.warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                    @error('adjustment.0.warehouse_id')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label for="adjustment[0][reason]" class="label text-green has-text-weight-normal">Reason <sup class="has-text-danger">*</sup></label>
                                <div class="control has-icons-left">
                                    <textarea name="adjustment[0][reason]" id="adjustment[0][reason]" cols="30" rows="3" class="textarea pl-6" placeholder="Describe reason for adjusting this product level">{{ old('adjustment.0.reason') ?? '' }}</textarea>
                                    <span class="icon is-large is-left">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    @error('adjustment.0.reason')
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
                    @if (old('adjustment.' . $i . '.product_id') || old('adjustment.' . $i . '.quantity'))
                        <div class="has-text-weight-medium has-text-left">
                            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                                Item {{ $i + 1 }}
                            </span>
                        </div>
                        <div class="box has-background-white-bis radius-top-0">
                            <div name="adjustmentFormGroup" class="columns is-marginless is-multiline">
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="adjustment[{{ $i }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                        <div class="control has-icons-left">
                                            <div class="select is-fullwidth">
                                                <select id="adjustment[{{ $i }}][product_id]" name="adjustment[{{ $i }}][product_id]" onchange="getProductSelected(this.id, this.value)">
                                                    <option selected disabled>Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ old('adjustment.' . $i . '.product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="icon is-small is-left">
                                                <i class="fas fa-th"></i>
                                            </div>
                                            @error('adjustment.' . $i . '.product_id')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <label for="adjustment[{{ $i }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                    <div class="field has-addons">
                                        <div class="control has-icons-left is-expanded">
                                            <input id="adjustment[{{ $i }}][quantity]" name="adjustment[{{ $i }}][quantity]" type="number" class="input" placeholder="Quantity" value="{{ old('adjustment.' . $i . '.quantity') ?? '' }}">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-balance-scale"></i>
                                            </span>
                                            @error('adjustment.' . $i . '.quantity')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="control">
                                            <button id="adjustment[{{ $i }}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="adjustment[{{ $i }}][is_subtract]" class="label text-green has-text-weight-normal"> Type <sup class="has-text-danger">*</sup> </label>
                                        <div class="control has-icons-left">
                                            <div class="select is-fullwidth">
                                                <select id="adjustment[{{ $i }}][is_subtract]" name="adjustment[{{ $i }}][is_subtract]">
                                                    <option value="0" {{ old('adjustment.' . $i . '.is_subtract') == 0 ? 'selected' : '' }}> Add </option>
                                                    <option value="1" {{ old('adjustment.' . $i . '.is_subtract') == 1 ? 'selected' : '' }}> Subtract </option>
                                                </select>
                                            </div>
                                            <div class="icon is-small is-left">
                                                <i class="fas fa-sort"></i>
                                            </div>
                                            @error('adjustment.' . $i . '.is_subtract')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="adjustment[{{ $i }}][warehouse_id]" class="label text-green has-text-weight-normal"> Warehouse <sup class="has-text-danger">*</sup> </label>
                                        <div class="control has-icons-left">
                                            <div class="select is-fullwidth">
                                                <select id="adjustment[{{ $i }}][warehouse_id]" name="adjustment[{{ $i }}][warehouse_id]">
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}" {{ old('adjustment.' . $i . '.warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="icon is-small is-left">
                                                <i class="fas fa-warehouse"></i>
                                            </div>
                                            @error('adjustment.' . $i . '.warehouse_id')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="adjustment[{{ $i }}][reason]" class="label text-green has-text-weight-normal">Reason <sup class="has-text-danger">*</sup></label>
                                        <div class="control has-icons-left">
                                            <textarea name="adjustment[{{ $i }}][reason]" id="adjustment[{{ $i }}][reason]" cols="30" rows="3" class="textarea pl-6" placeholder="Describe reason for adjusting this product level">{{ old('adjustment.' . $i . '.reason') ?? '' }}</textarea>
                                            <span class="icon is-large is-left">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                            @error('adjustment.' . $i . '.reason')
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
                <div id="adjustmentFormWrapper"></div>
                <button id="addNewAdjustmentForm" type="button" class="button bg-purple has-text-white is-small ml-3 mt-3">
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
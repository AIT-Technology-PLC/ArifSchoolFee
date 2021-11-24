@extends('layouts.app')

@section('title')
    Create New Damage
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Damage
            </h1>
        </div>
        <form
            id="formOne"
            action="{{ route('damages.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="code"
                                class="label text-green has-text-weight-normal"
                            >Damage Number <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    class="input"
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $currentDamageCode }}"
                                >
                                <span class="icon is-large is-left">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                @error('code')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="issued_on"
                                class="label text-green has-text-weight-normal"
                            > Issued On <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    class="input"
                                    type="datetime-local"
                                    name="issued_on"
                                    id="issued_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('issued_on') ?? now()->toDateTimeLocalString() }}"
                                >
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                @error('issued_on')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="description"
                                class="label text-green has-text-weight-normal"
                            > Description <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea
                                    name="description"
                                    id="description"
                                    cols="30"
                                    rows="3"
                                    class="textarea pl-6"
                                    placeholder="Description or note to be taken"
                                >{{ old('description') ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-edit"></i>
                                </span>
                                @error('description')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div id="damage-details">
                    @foreach (old('damage', [[]]) as $damageDetail)
                        <div class="damage-detail mx-3">
                            <div class="field has-addons mb-0 mt-5">
                                <div class="control">
                                    <span
                                        name="item-number"
                                        class="tag bg-green has-text-white is-medium is-radiusless"
                                    >
                                        Item {{ $loop->iteration }}
                                    </span>
                                </div>
                                <div class="control">
                                    <button
                                        name="remove-detail-button"
                                        type="button"
                                        class="tag bg-lightgreen has-text-white is-medium is-radiusless is-pointer"
                                    >
                                        <span class="icon text-green">
                                            <i class="fas fa-times-circle"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="box has-background-white-bis radius-top-0">
                                <div
                                    name="damageFormGroup"
                                    class="columns is-marginless is-multiline"
                                >
                                    <div class="column is-6">
                                        <div class="field">
                                            <label
                                                for="damage[{{ $loop->index }}][product_id]"
                                                class="label text-green has-text-weight-normal"
                                            > Product <sup class="has-text-danger">*</sup> </label>
                                            <div class="control has-icons-left">
                                                <x-common.product-list
                                                    tags="false"
                                                    name="damage[{{ $loop->index }}]"
                                                    selected-product-id="{{ $damageDetail['product_id'] ?? '' }}"
                                                />
                                                <div class="icon is-small is-left">
                                                    <i class="fas fa-th"></i>
                                                </div>
                                                @error('damage.' . $loop->index . '.product_id')
                                                    <span
                                                        class="help has-text-danger"
                                                        role="alert"
                                                    >
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <div class="field">
                                            <label
                                                for="damage[{{ $loop->index }}][warehouse_id]"
                                                class="label text-green has-text-weight-normal"
                                            > From <sup class="has-text-danger">*</sup> </label>
                                            <div class="control has-icons-left">
                                                <div class="select is-fullwidth">
                                                    <select
                                                        id="damage[{{ $loop->index }}][warehouse_id]"
                                                        name="damage[{{ $loop->index }}][warehouse_id]"
                                                    >
                                                        @foreach ($warehouses as $warehouse)
                                                            <option
                                                                value="{{ $warehouse->id }}"
                                                                {{ ($damageDetail['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                                            >{{ $warehouse->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="icon is-small is-left">
                                                    <i class="fas fa-warehouse"></i>
                                                </div>
                                                @error('damage.' . $loop->index . '.warehouse_id')
                                                    <span
                                                        class="help has-text-danger"
                                                        role="alert"
                                                    >
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <label
                                            for="damage[{{ $loop->index }}][quantity]"
                                            class="label text-green has-text-weight-normal"
                                        >Quantity <sup class="has-text-danger">*</sup> </label>
                                        <div class="field has-addons">
                                            <div class="control has-icons-left is-expanded">
                                                <input
                                                    id="damage[{{ $loop->index }}][quantity]"
                                                    name="damage[{{ $loop->index }}][quantity]"
                                                    type="number"
                                                    class="input"
                                                    placeholder="Quantity"
                                                    value="{{ $damageDetail['quantity'] ?? '' }}"
                                                >
                                                <span class="icon is-small is-left">
                                                    <i class="fas fa-balance-scale"></i>
                                                </span>
                                                @error('damage.' . $loop->index . '.quantity')
                                                    <span
                                                        class="help has-text-danger"
                                                        role="alert"
                                                    >
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="control">
                                                <button
                                                    id="damage[{{ $loop->index }}][product_id]Quantity"
                                                    class="button bg-green has-text-white"
                                                    type="button"
                                                ></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <div class="field">
                                            <label
                                                for="damage[{{ $loop->index }}][description]"
                                                class="label text-green has-text-weight-normal"
                                            >Additional Notes <sup class="has-text-danger"></sup></label>
                                            <div class="control has-icons-left">
                                                <textarea
                                                    name="damage[{{ $loop->index }}][description]"
                                                    id="damage[{{ $loop->index }}][description]"
                                                    cols="30"
                                                    rows="3"
                                                    class="textarea pl-6"
                                                    placeholder="Description or note to be taken"
                                                >{{ $damageDetail['description'] ?? '' }}</textarea>
                                                <span class="icon is-large is-left">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                @error('damage.' . $loop->index . '.description')
                                                    <span
                                                        class="help has-text-danger"
                                                        role="alert"
                                                    >
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
                <button
                    id="addNewDamageForm"
                    type="button"
                    class="button bg-purple has-text-white is-small ml-3 mt-6"
                >
                    Add More Item
                </button>
            </div>
            <div class="box radius-top-0">
                <x-common.save-button />
            </div>
        </form>
    </section>
@endsection

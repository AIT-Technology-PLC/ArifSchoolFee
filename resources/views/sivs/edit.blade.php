@extends('layouts.app')

@section('title')
    Edit SIV
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit SIV
            </h1>
        </div>
        <form
            id="formOne"
            action="{{ route('sivs.update', $siv->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="code"
                                class="label text-green has-text-weight-normal"
                            >SIV Number <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    class="input"
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $siv->code }}"
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
                                for="issued_to"
                                class="label text-green has-text-weight-normal"
                            > Customer <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <x-common.customer-list
                                        id="issued_to"
                                        name="issued_to"
                                        value="company_name"
                                        :selected-customer-id="$siv->issued_to ?? ''"
                                    />
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-user"></i>
                                </div>
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
                                    value="{{ $siv->issued_on->toDateTimeLocalString() }}"
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
                                for="purpose"
                                class="label text-green has-text-weight-normal"
                            > Purpose <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select
                                        id="purpose"
                                        name="purpose"
                                    >
                                        <option
                                            disabled
                                            selected
                                        > Select Purpose </option>
                                        <option
                                            value="DO"
                                            {{ $siv->purpose == 'DO' ? 'selected' : '' }}
                                        > Delivery Order </option>
                                        <option
                                            value="Transfer"
                                            {{ $siv->purpose == 'Transfer' ? 'selected' : '' }}
                                        > Transfer </option>
                                        <option
                                            value="Expo"
                                            {{ $siv->purpose == 'Expo' ? 'selected' : '' }}
                                        > Expo </option>
                                        <option value="">None</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-question"></i>
                                </div>
                                @error('purpose')
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
                                for="ref_num"
                                class="label text-green has-text-weight-normal"
                            >Ref N<u>o</u> <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    class="input"
                                    type="number"
                                    name="ref_num"
                                    id="ref_num"
                                    value="{{ $siv->ref_num ?? '' }}"
                                >
                                <span class="icon is-large is-left">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                @error('ref_num')
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
                            for="received_by"
                            class="label text-green has-text-weight-normal"
                        >Received By <sup class="has-text-danger"></sup> </label>
                        <div class="field">
                            <div class="control has-icons-left is-expanded">
                                <input
                                    class="input"
                                    type="text"
                                    name="received_by"
                                    id="received_by"
                                    placeholder="Reciever Name"
                                    value="{{ $siv->received_by ?? '' }}"
                                >
                                <span class="icon is-large is-left">
                                    <i class="fas fa-user"></i>
                                </span>
                                @error('received_by')
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
                            for="delivered_by"
                            class="label text-green has-text-weight-normal"
                        >Delivered By <sup class="has-text-danger"></sup> </label>
                        <div class="field">
                            <div class="control has-icons-left is-expanded">
                                <input
                                    class="input"
                                    type="text"
                                    name="delivered_by"
                                    id="delivered_by"
                                    placeholder="Delivered By"
                                    value="{{ $siv->delivered_by ?? '' }}"
                                >
                                <span class="icon is-large is-left">
                                    <i class="fas fa-user"></i>
                                </span>
                                @error('delivered_by')
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
                    <div class="column is-12">
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
                                    class="summernote textarea"
                                    placeholder="Description or note to be taken"
                                >{{ $siv->description ?? '' }}</textarea>
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

                @foreach ($siv->sivDetails as $sivDetail)
                    <div class="has-text-weight-medium has-text-left">
                        <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                            Item {{ $loop->index + 1 }}
                        </span>
                    </div>
                    <div
                        x-data="productDataProvider({{ $sivDetail->product_id }})"
                        class="box has-background-white-bis radius-top-0"
                    >
                        <div
                            name="sivFormGroup"
                            class="columns is-marginless is-multiline"
                        >
                            <div class="column is-6">
                                <label
                                    for="siv[{{ $loop->index }}][product_id]"
                                    class="label text-green has-text-weight-normal"
                                >
                                    Product <sup class="has-text-danger">*</sup>
                                </label>
                                <div class="field has-addons">
                                    <div
                                        class="control has-icons-left"
                                        style="width: 30%"
                                    >
                                        <x-common.category-list
                                            x-model="selectedCategory"
                                            x-on:change="getProductsByCategory"
                                        />
                                    </div>
                                    <div class="control has-icons-left is-expanded">
                                        <x-common.product-list
                                            tags="false"
                                            name="siv[{{ $loop->index }}]"
                                            selected-product-id="{{ $sivDetail->product_id }}"
                                            x-init="select2"
                                        />
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-th"></i>
                                        </div>
                                        @error('siv.' . $loop->index . '.product_id')
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
                                        for="siv[{{ $loop->index }}][warehouse_id]"
                                        class="label text-green has-text-weight-normal"
                                    > From <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <div class="select is-fullwidth">
                                            <select
                                                id="siv[{{ $loop->index }}][warehouse_id]"
                                                name="siv[{{ $loop->index }}][warehouse_id]"
                                            >
                                                @foreach ($warehouses as $warehouse)
                                                    <option
                                                        value="{{ $warehouse->id }}"
                                                        {{ $sivDetail->warehouse_id == $warehouse->id ? 'selected' : '' }}
                                                    >{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-warehouse"></i>
                                        </div>
                                        @error('siv.' . $loop->index . '.warehouse_id')
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
                                    for="siv[{{ $loop->index }}][quantity]"
                                    class="label text-green has-text-weight-normal"
                                >Quantity <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input
                                            id="siv[{{ $loop->index }}][quantity]"
                                            name="siv[{{ $loop->index }}][quantity]"
                                            type="number"
                                            class="input"
                                            placeholder="Quantity"
                                            value="{{ $sivDetail->quantity ?? '' }}"
                                        >
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('siv.' . $loop->index . '.quantity')
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
                                            id="siv[{{ $loop->index }}][product_id]Quantity"
                                            class="button bg-green has-text-white"
                                            type="button"
                                            x-text="product.unit_of_measurement"
                                        ></button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label
                                        for="siv[{{ $loop->index }}][description]"
                                        class="label text-green has-text-weight-normal"
                                    >Additional Notes <sup class="has-text-danger"></sup></label>
                                    <div class="control has-icons-left">
                                        <textarea
                                            name="siv[{{ $loop->index }}][description]"
                                            id="siv[{{ $loop->index }}][description]"
                                            cols="30"
                                            rows="3"
                                            class="textarea pl-6"
                                            placeholder="Description or note to be taken"
                                        >{{ $sivDetail->description ?? '' }}</textarea>
                                        <span class="icon is-large is-left">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        @error('siv.' . $loop->index . '.description')
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
                @endforeach
            </div>
            <div class="box radius-top-0">
                <x-common.save-button />
            </div>
        </form>
    </section>
@endsection

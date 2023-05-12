@extends('layouts.app')

@section('title')
    Create New Tender
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Tender
            </h1>
        </div>
        <form
            id="formOne"
            action="{{ route('tenders.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
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
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="code"
                                class="label text-green has-text-weight-normal"
                            >Tender No <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    class="input"
                                    type="text"
                                    name="code"
                                    id="code"
                                    placeholder="Tender No"
                                    value="{{ old('code') ?? '' }}"
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
                                for="type"
                                class="label text-green has-text-weight-normal"
                            > Type <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select
                                        id="type"
                                        name="type"
                                    >
                                        <option
                                            selected
                                            disabled
                                        >Select Type</option>
                                        <option
                                            value="NCB"
                                            {{ old('type') == 'NCB' ? 'selected' : '' }}
                                        >NCB</option>
                                        <option
                                            value="ICB"
                                            {{ old('type') == 'ICB' ? 'selected' : '' }}
                                        >ICB</option>
                                    </select>
                                </div>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-columns"></i>
                                </span>
                                @error('type')
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
                                for="status"
                                class="label text-green has-text-weight-normal"
                            > Status <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select
                                        id="status"
                                        name="status"
                                    >
                                        <option
                                            selected
                                            disabled
                                        >Select Status</option>
                                        @foreach ($tenderStatuses as $tenderStatus)
                                            <option
                                                value="{{ $tenderStatus->status }}"
                                                {{ old('status') == $tenderStatus->status ? 'selected' : '' }}
                                            >{{ $tenderStatus->status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-info"></i>
                                </span>
                                @error('status')
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
                            for="bid_bond_type"
                            class="label text-green has-text-weight-normal"
                        > Bid Bond <span class="has-text-weight-light is-size-7">(Type, Amount, Validity)</span> <sup class="has-text-danger"></sup> </label>
                        <div class="field has-addons">
                            <p class="control">
                                <input
                                    name="bid_bond_type"
                                    class="input"
                                    type="text"
                                    placeholder="Type"
                                    value="{{ old('bid_bond_type') ?? '' }}"
                                >
                            </p>
                            <p class="control">
                                <input
                                    name="bid_bond_amount"
                                    class="input has-background-white-ter"
                                    type="text"
                                    placeholder="Amount"
                                    value="{{ old('bid_bond_amount') ?? '' }}"
                                >
                            </p>
                            <p class="control">
                                <input
                                    name="bid_bond_validity"
                                    class="input"
                                    type="text"
                                    placeholder="Validity"
                                    value="{{ old('bid_bond_validity') ?? '' }}"
                                >
                            </p>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="customer_id"
                                class="label text-green has-text-weight-normal"
                            > Customer <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <x-common.customer-list :selected-id="old('customer_id') ?? ''" />
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-address-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="price"
                                class="label text-green has-text-weight-normal"
                            > Price <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea
                                    name="price"
                                    id="price"
                                    cols="30"
                                    rows="3"
                                    class="textarea pl-6"
                                    placeholder="Price Description"
>{{ old('price') ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                                @error('price')
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
                                for="payment_term"
                                class="label text-green has-text-weight-normal"
                            > Payment Term <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea
                                    name="payment_term"
                                    id="payment_term"
                                    cols="30"
                                    rows="3"
                                    class="textarea pl-6"
                                    placeholder="Payment Term"
>{{ old('payment_term') ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </span>
                                @error('payment_term')
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
                <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3 mx-3 mt-5">
                    <h1 class="text-green is-size-5 is-uppercase has-text-weight-semibold has-text-centered">
                        Tender Schedules
                    </h1>
                </div>
                <div class="box is-radiusless mx-3">
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-6">
                            <div class="field">
                                <label
                                    for="published_on"
                                    class="label text-green has-text-weight-normal"
                                > Published On <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <input
                                        class="input"
                                        type="date"
                                        name="published_on"
                                        id="published_on"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ old('published_on') ?? now()->toDateString() }}"
                                    >
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('published_on')
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
                                    for="closing_date"
                                    class="label text-green has-text-weight-normal"
                                > Closing Date <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <input
                                        class="input"
                                        type="datetime-local"
                                        name="closing_date"
                                        id="closing_date"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ old('closing_date') ?? '' }}"
                                    >
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('closing_date')
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
                                    for="opening_date"
                                    class="label text-green has-text-weight-normal"
                                > Opening Date <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <input
                                        class="input"
                                        type="datetime-local"
                                        name="opening_date"
                                        id="opening_date"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ old('opening_date') ?? '' }}"
                                    >
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('opening_date')
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
                                    for="clarify_on"
                                    class="label text-green has-text-weight-normal"
                                > Clarification Date <sup class="has-text-danger"></sup> </label>
                                <div class="control has-icons-left">
                                    <input
                                        class="input"
                                        type="date"
                                        name="clarify_on"
                                        id="clarify_on"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ old('clarify_on') ?? '' }}"
                                    >
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('clarify_on')
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
                                    for="visit_on"
                                    class="label text-green has-text-weight-normal"
                                > Visiting Date <sup class="has-text-danger"></sup> </label>
                                <div class="control has-icons-left">
                                    <input
                                        class="input"
                                        type="date"
                                        name="visit_on"
                                        id="visit_on"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ old('visit_on') ?? '' }}"
                                    >
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('visit_on')
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
                                    for="premeet_on"
                                    class="label text-green has-text-weight-normal"
                                > Pre-meeting Date <sup class="has-text-danger"></sup> </label>
                                <div class="control has-icons-left">
                                    <input
                                        class="input"
                                        type="date"
                                        name="premeet_on"
                                        id="premeet_on"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ old('premeet_on') ?? '' }}"
                                    >
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('premeet_on')
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
                <div
                    x-data="tenderMasterDetailForm({{ json_encode(session()->getOldInput()) }})"
                    x-init="setErrors({{ json_encode($errors->get('lot.*')) }})"
                    class="mx-3"
                >
                    <template
                        x-for="(lot, lotIndex) in lots"
                        x-bind:key="lotIndex"
                    >
                        <section>
                            <div class="field has-addons mb-0 mt-5">
                                <div class="control">
                                    <span
                                        class="tag bg-purple has-text-white is-medium is-radiusless"
                                        x-text="`Lot #${lotIndex + 1}`"
                                    >
                                    </span>
                                </div>
                                <div class="control">
                                    <button
                                        type="button"
                                        class="tag bg-lightpurple has-text-white is-medium is-radiusless is-pointer"
                                        x-on:click="removeLot(lotIndex)"
                                    >
                                        <span class="icon text-purple">
                                            <i class="fas fa-times-circle"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="box is-shadowless has-background-white-bis">
                                <template
                                    x-for="(lotDetail, lotDetailIndex) in lot.lotDetails"
                                    x-bind:key="lotDetailIndex"
                                >
                                    <div
                                        x-data="productDataProvider(lotDetail.product_id)"
                                        class="mx-3"
                                    >
                                        <div class="field has-addons mb-0 mt-5">
                                            <div class="control">
                                                <span
                                                    class="tag bg-green has-text-white is-medium is-radiusless"
                                                    x-text="`Item ${lotDetailIndex + 1}`"
                                                >
                                                </span>
                                            </div>
                                            <div class="control">
                                                <button
                                                    type="button"
                                                    class="tag bg-lightgreen has-text-white is-medium is-radiusless is-pointer"
                                                    x-on:click="removeLotDetail(lotIndex, lotDetailIndex)"
                                                >
                                                    <span class="icon text-green">
                                                        <i class="fas fa-times-circle"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="box is-shadowless has-background-white-ter radius-top-0">
                                            <div class="columns is-marginless is-multiline">
                                                <div class="column is-6">
                                                    <label
                                                        x-bind:for="`lot[${lotIndex}][lotDetails][${lotDetailIndex}][product_id]`"
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
                                                                x-bind:name="`lot[${lotIndex}][lotDetails][${lotDetailIndex}][product_id]`"
                                                                x-bind:id="`lot[${lotIndex}][lotDetails][${lotDetailIndex}][product_id]`"
                                                                x-init="select2();
                                                                select2Tender(lotIndex, lotDetailIndex)"
                                                                x-model="lotDetail.product_id"
                                                            />
                                                            <div class="icon is-small is-left">
                                                                <i class="fas fa-th"></i>
                                                            </div>
                                                            <span
                                                                class="help has-text-danger"
                                                                x-text="errors[`lot.${lotIndex}.lotDetails.${lotDetailIndex}.product_id`]"
                                                            ></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-6">
                                                    <label
                                                        x-bind:for="`lot[${lotIndex}][lotDetails][${lotDetailIndex}][quantity]`"
                                                        class="label text-green has-text-weight-normal"
                                                    >Quantity <sup class="has-text-danger">*</sup> </label>
                                                    <div class="field has-addons">
                                                        <div class="control has-icons-left is-expanded">
                                                            <input
                                                                x-bind:id="`lot[${lotIndex}][lotDetails][${lotDetailIndex}][quantity]`"
                                                                x-bind:name="`lot[${lotIndex}][lotDetails][${lotDetailIndex}][quantity]`"
                                                                type="number"
                                                                class="input"
                                                                placeholder="Quantity"
                                                                x-model="lotDetail.quantity"
                                                            >
                                                            <span class="icon is-small is-left">
                                                                <i class="fas fa-balance-scale"></i>
                                                            </span>
                                                            <span
                                                                class="help has-text-danger"
                                                                x-text="errors[`lot.${lotIndex}.lotDetails.${lotDetailIndex}.quantity`]"
                                                            ></span>
                                                        </div>
                                                        <div class="control">
                                                            <button
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
                                                            x-bind:for="`lot[${lotIndex}][lotDetails][${lotDetailIndex}][description]`"
                                                            class="label text-green has-text-weight-normal"
                                                        >Additional Notes <sup class="has-text-danger"></sup></label>
                                                        <div class="control has-icons-left">
                                                            <textarea
                                                                x-bind:id="`lot[${lotIndex}][lotDetails][${lotDetailIndex}][description]`"
                                                                x-bind:name="`lot[${lotIndex}][lotDetails][${lotDetailIndex}][description]`"
                                                                cols="30"
                                                                rows="3"
                                                                class="textarea pl-6"
                                                                placeholder="Description or note to be taken"
                                                                x-model="lotDetail.description"
></textarea>
                                                            <span class="icon is-large is-left">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            <span
                                                                class="help has-text-danger"
                                                                x-text="errors[`lot.${lotIndex}.lotDetails.${lotDetailIndex}.description`]"
                                                            ></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <button
                                    type="button"
                                    class="button bg-green has-text-white is-small ml-3 mt-5"
                                    x-on:click="addLotDetail(lotIndex)"
                                >
                                    <span class="icon is-small">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span> Add Product </span>
                                </button>
                            </div>
                        </section>
                    </template>
                    <button
                        type="button"
                        class="button bg-purple has-text-white is-small ml-3 mt-6"
                        x-on:click="addLot()"
                    >
                        <span class="icon is-small">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span> Add Lot </span>
                    </button>
                </div>
            </div>
            <div class="box radius-top-0">
                <x-common.save-button />
            </div>
        </form>
    </section>

    @can('Create Customer')
        <div x-bind:class="Alpine.store('openCreateCustomerModal') ? '' : 'is-hidden'">
            <livewire:create-customer />
        </div>
    @endcan
@endsection

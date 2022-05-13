@extends('layouts.app')

@section('title')
    Edit Reservation
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit Reservation
            </h1>
        </div>
        <form
            id="formOne"
            action="{{ route('reservations.update', $reservation->id) }}"
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
                            >Reservation Number <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    class="input"
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $reservation->code }}"
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
                                for="customer_id"
                                class="label text-green has-text-weight-normal"
                            > Customer <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <x-common.customer-list :selected-id="$reservation->customer_id ?? ''" />
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
                                    value="{{ $reservation->issued_on->toDateTimeLocalString() }}"
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
                                for="expires_on"
                                class="label text-green has-text-weight-normal"
                            > Expires On <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    class="input"
                                    type="date"
                                    name="expires_on"
                                    id="expires_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ $reservation->expires_on->toDateString() }}"
                                >
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                @error('expires_on')
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
                            >Description <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <textarea
                                    name="description"
                                    id="description"
                                    cols="30"
                                    rows="3"
                                    class="textarea pl-6"
                                    placeholder="Description or note to be taken"
>{{ $reservation->description ?? '' }}</textarea>
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
                <div class="box radius-bottom-0 has-background-white-bis p-3 mx-3 mt-5 mb-0">
                    <h1 class="text-green is-size-5">
                        Payment Details
                    </h1>
                </div>
                <div
                    class="box is-radiusless mx-3 mb-6"
                    x-data="cashReceivedType('{{ $reservation->payment_type }}', '{{ $reservation->cash_received_type }}', {{ $reservation->cash_received }})"
                >
                    <div class="columns is-marginless is-multiline">
                        <div class="column {{ userCompany()->isDiscountBeforeVAT() ? 'is-hidden' : '' }}">
                            <label
                                for="discount"
                                class="label text-green has-text-weight-normal"
                            >Discount<sup class="has-text-danger"></sup> </label>
                            <div class="field">
                                <div class="control has-icons-left is-expanded">
                                    <input
                                        id="discount"
                                        name="discount"
                                        type="number"
                                        class="input"
                                        placeholder="Discount in Percentage"
                                        value="{{ $reservation->discount * 100 ?? '' }}"
                                    >
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-percent"></i>
                                    </span>
                                    @error('discount')
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
                        <div class="column">
                            <div class="field">
                                <label
                                    for="payment_type"
                                    class="label text-green has-text-weight-normal"
                                >Payment Method <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select
                                            id="payment_type"
                                            name="payment_type"
                                            x-model="paymentType"
                                        >
                                            <option disabled>Select Payment</option>
                                            <option value="Cash Payment">Cash Payment</option>
                                            <option value="Credit Payment">Credit Payment</option>
                                        </select>
                                    </div>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                </div>
                                @error('payment_type')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div
                            class="column"
                            x-cloak
                            x-bind:class="{ 'is-hidden': isPaymentInCash() }"
                        >
                            <label
                                for="cash_received"
                                class="label text-green has-text-weight-normal"
                            >Cash Received <sup class="has-text-danger">*</sup></label>
                            <div class="field has-addons">
                                <div class="control">
                                    <span class="select">
                                        <select
                                            name="cash_received_type"
                                            x-model="cashReceivedType"
                                        >
                                            <option disabled>Type</option>
                                            <option
                                                value="amount"
                                                {{ $reservation->cash_received_type == 'amount' ? 'selected' : '' }}
                                            >Amount</option>
                                            <option
                                                value="percent"
                                                {{ $reservation->cash_received_type == 'percent' ? 'selected' : '' }}
                                            >Percent</option>
                                        </select>
                                    </span>
                                </div>
                                <div class="control has-icons-left is-expanded">
                                    <input
                                        class="input"
                                        type="number"
                                        name="cash_received"
                                        id="cash_received"
                                        x-model="cashReceived"
                                        placeholder="eg. 50"
                                    >
                                    <span class="icon is-large is-left">
                                        <i class="fas fa-money-bill"></i>
                                    </span>
                                    @error('cash_received')
                                        <span
                                            class="help has-text-danger"
                                            role="alert"
                                        >
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    @error('cash_received_type')
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
                        <div
                            class="column"
                            x-cloak
                            x-bind:class="{ 'is-hidden': isPaymentInCash() }"
                        >
                            <div class="
                            field">
                                <label
                                    for="due_date"
                                    class="label text-green has-text-weight-normal"
                                > Credit Due Date <sup class="has-text-danger"></sup> </label>
                                <div class="control has-icons-left">
                                    <input
                                        class="input"
                                        type="date"
                                        name="due_date"
                                        id="due_date"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ $reservation->due_date ? $reservation->due_date->toDateString() : '' }}"
                                    >
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('due_date')
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
                @foreach ($reservation->reservationDetails as $reservationDetail)
                    <div class="has-text-weight-medium has-text-left mt-5">
                        <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                            Item {{ $loop->index + 1 }}
                        </span>
                    </div>
                    <div
                        x-data="productDataProvider({{ $reservationDetail->product_id }}, {{ $reservationDetail->originalUnitPrice ?? 0.0 }})"
                        class="box has-background-white-bis radius-top-0"
                    >
                        <div
                            name="reservationFormGroup"
                            class="columns is-marginless is-multiline"
                        >
                            <div class="column is-6">
                                <label
                                    for="reservation[{{ $loop->index }}][product_id]"
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
                                            name="reservation[{{ $loop->index }}]"
                                            selected-product-id="{{ $reservationDetail->product_id }}"
                                            x-init="select2"
                                        />
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-th"></i>
                                        </div>
                                        @error('reservation.0.product_id')
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
                                        for="reservation[{{ $loop->index }}][warehouse_id]"
                                        class="label text-green has-text-weight-normal"
                                    > From <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <div class="select is-fullwidth">
                                            <select
                                                id="reservation[{{ $loop->index }}][warehouse_id]"
                                                name="reservation[{{ $loop->index }}][warehouse_id]"
                                            >
                                                @foreach ($warehouses as $warehouse)
                                                    <option
                                                        value="{{ $warehouse->id }}"
                                                        {{ $reservationDetail->warehouse_id == $warehouse->id ? 'selected' : '' }}
                                                    >{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-warehouse"></i>
                                        </div>
                                        @error('reservation.0.warehouse_id')
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
                                    for="reservation[{{ $loop->index }}][quantity]"
                                    class="label text-green has-text-weight-normal"
                                >Quantity <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input
                                            id="reservation[{{ $loop->index }}][quantity]"
                                            name="reservation[{{ $loop->index }}][quantity]"
                                            type="number"
                                            class="input"
                                            placeholder="Quantity"
                                            value="{{ $reservationDetail->quantity }}"
                                        >
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('reservation.0.quantity')
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
                                            id="reservation[{{ $loop->index }}][product_id]Quantity"
                                            class="button bg-green has-text-white"
                                            type="button"
                                            x-text="product.unit_of_measurement"
                                        >
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label
                                    for="reservation[{{ $loop->index }}][unit_price]"
                                    class="label text-green has-text-weight-normal"
                                >Unit Price<sup class="has-text-weight-light"> ({{ userCompany()->getPriceMethod() }})</sup> <sup class="has-text-danger"></sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input
                                            id="reservation[{{ $loop->index }}][unit_price]"
                                            name="reservation[{{ $loop->index }}][unit_price]"
                                            type="number"
                                            class="input"
                                            placeholder="Sale Price"
                                            :readonly="isDisabled"
                                            x-model="product.price"
                                        >
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-money-bill"></i>
                                        </span>
                                        @error('reservation.0.unit_price')
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
                                            id="reservation[{{ $loop->index }}][product_id]Price"
                                            class="button bg-green has-text-white"
                                            type="button"
                                            x-text="product.unit_of_measurement && `Per ${product.unit_of_measurement}`"
                                        >
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6 {{ userCompany()->isDiscountBeforeVAT() ? '' : 'is-hidden' }}">
                                <label
                                    for="reservation[{{ $loop->index }}][discount]"
                                    class="label text-green has-text-weight-normal"
                                >Discount <sup class="has-text-danger"></sup> </label>
                                <div class="field">
                                    <div class="control has-icons-left is-expanded">
                                        <input
                                            id="reservation[{{ $loop->index }}][discount]"
                                            name="reservation[{{ $loop->index }}][discount]"
                                            type="number"
                                            class="input"
                                            placeholder="Discount in Percentage"
                                            value="{{ $reservationDetail->discount * 100 ?? '' }}"
                                        >
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-percent"></i>
                                        </span>
                                        @error('reservation.' . $loop->index . '.discount')
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
                                        for="reservation[{{ $loop->index }}][description]"
                                        class="label text-green has-text-weight-normal"
                                    >Additional Notes <sup class="has-text-danger"></sup></label>
                                    <div class="control has-icons-left">
                                        <textarea
                                            name="reservation[{{ $loop->index }}][description]"
                                            id="reservation[{{ $loop->index }}][description]"
                                            cols="30"
                                            rows="3"
                                            class="textarea pl-6"
                                            placeholder="Description or note to be taken"
>{{ $reservationDetail->description ?? '' }}</textarea>
                                        <span class="icon is-large is-left">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        @error('reservation.0.description')
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

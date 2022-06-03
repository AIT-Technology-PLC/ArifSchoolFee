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
        <form
            id="formOne"
            action="{{ route('gdns.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                DO Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $currentGdnCode }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="customer_id">
                                Customer <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left select is-fullwidth">
                                <x-common.customer-list :selected-id="old('customer_id') ?? ''" />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="customer_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (isFeatureEnabled('Sale Management'))
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="sale_id">
                                    Receipt No <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        class=" is-fullwidth"
                                        id="sale_id"
                                        name="sale_id"
                                    >
                                        <option
                                            selected
                                            disabled
                                        >Select Sale</option>
                                        @foreach ($sales as $sale)
                                            <option
                                                value="{{ $sale->id }}"
                                                {{ old('sale_id') == $sale->id ? 'selected' : '' }}
                                            >{{ $sale->code ?? '' }}</option>
                                        @endforeach
                                        <option value="">None</option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-cash-register"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="sale_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="issued_on">
                                Issued On<sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="datetime-local"
                                    name="issued_on"
                                    id="issued_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('issued_on') ?? now()->toDateTimeLocalString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="issued_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description<sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="summernote textarea"
                                    placeholder="Description or note to be taken"
                                >{{ old('description') ?? '' }}
                                </x-forms.textarea>
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
                <div class="box radius-bottom-0 has-background-white-bis p-3 mx-3 mt-5 mb-0">
                    <h1 class="text-green is-size-5">
                        Payment Details
                    </h1>
                </div>
                <div
                    class="box is-radiusless mx-3 mb-6"
                    x-data="cashReceivedType('{{ old('payment_type') }}', '{{ old('cash_received_type') }}', '{{ old('cash_received') }}', '{{ old('due_date') }}')"
                >
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-12 {{ userCompany()->isDiscountBeforeVAT() ? 'is-hidden' : '' }}">
                            <x-forms.label for="discount">
                                discount <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        id="discount"
                                        name="discount"
                                        type="number"
                                        placeholder="Discount in Percentage"
                                        value="{{ old('discount') ?? '' }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-percent"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="discount" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column">
                            <x-forms.field>
                                <x-forms.label for="payment_type">
                                    Payment Method<sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="payment_type"
                                        name="payment_type"
                                        x-model="paymentType"
                                        x-on:change="changePaymentMethod"
                                    >
                                        <option
                                            selected
                                            disabled
                                            value=""
                                        >Select Payment</option>
                                        <option
                                            value="Cash Payment"
                                            {{ old('payment_type') == 'Cash Payment' ? 'selected' : '' }}
                                        >Cash Payment</option>
                                        <option
                                            value="Credit Payment"
                                            {{ old('payment_type') == 'Credit Payment' ? 'selected' : '' }}
                                        >Credit Payment</option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-credit-card"
                                        class="is-small is-left"
                                    />
                                </x-forms.control>
                                <x-common.validation-error property="payment_type" />
                            </x-forms.field>
                        </div>
                        <div
                            class="column"
                            x-cloak
                            x-bind:class="{ 'is-hidden': isPaymentInCash() }"
                        >
                            <x-forms.label for="cash_received">
                                Cash Received <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="field has-addons">
                                <x-forms.control>
                                    <x-forms.select
                                        name="cash_received_type"
                                        x-model="cashReceivedType"
                                    >
                                        <option
                                            selected
                                            disabled
                                            value=""
                                        >Type</option>
                                        <option value="amount">Amount</option>
                                        <option value="percent">Percent</option>
                                    </x-forms.select>
                                </x-forms.control>
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="number"
                                        name="cash_received"
                                        id="cash_received"
                                        placeholder="eg. 50"
                                        x-model="cashReceived"
                                    />
                                    <x-common.icon
                                        name="fas fa-money-bill"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="cash_received" />
                                    <x-common.validation-error property="cash_received_type" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div
                            class="column"
                            x-cloak
                            x-bind:class="{ 'is-hidden': isPaymentInCash() }"
                        >
                            <x.forms.field>
                                <x-forms.label for="due_date">
                                    Credit Due Date <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        type="date"
                                        name="due_date"
                                        id="due_date"
                                        placeholder="mm/dd/yyyy"
                                        x-model="dueDate"
                                    />
                                    <x-common.icon
                                        name="fas fa-calendar-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="due_date" />
                                </x-forms.control>
                            </x.forms.field>
                        </div>
                    </div>
                </div>
            </div>
            @include('gdns.details-form', ['data' => session()->getOldInput()])
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </section>
@endsection

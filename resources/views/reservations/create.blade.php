@extends('layouts.app')

@section('title', 'Create New Reservation')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Reservations" />
        <form
            id="formOne"
            action="{{ route('reservations.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Reservation Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <div class="control has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    :readonly="!userCompany()->isEditingReferenceNumberEnabled()"
                                    value="{{ $currentReservationCode }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="code" />
                            </div>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="customer_id">
                                Customer <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="select is-fullwidth has-icons-left">
                                <x-common.customer-list :selected-id="old('customer_id') ?? ''" />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="customer_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="issued_on">
                                Issued On <sup class="has-text-danger">*</sup>
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
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="contact_id">
                                Contact <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left select is-fullwidth">
                                <x-common.contact-list :selected-id="old('contact_id') ?? ''" />
                                <x-common.icon
                                    name="fas fa-address-card"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="contact_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="expires_on">
                                Expires On <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="date"
                                    name="expires_on"
                                    id="expires_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('expires_on') ??
                                        now()->addDays(10)->toDateString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="expires_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="textarea summernote"
                                    placeholder="Description or note to be taken"
                                >
                                    {{ old('description') ?? '' }}
                                </x-forms.textarea>
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>

                <x-common.content-wrapper x-data="cashReceivedType('{{ old('payment_type') }}', '{{ old('cash_received_type') }}', '{{ old('cash_received') }}', '{{ old('due_date') }}', '{{ old('bank_name') }}', '{{ old('reference_number') }}')">
                    <x-content.header title="Payment Details" />
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-12 {{ userCompany()->isDiscountBeforeTax() ? 'is-hidden' : '' }}">
                                <x-forms.label for="discount">
                                    Discount <sup class="has-text-danger"></sup>
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
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="discount" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column">
                                <x-forms.field>
                                    <x-forms.label for="payment_type">
                                        Payment Method <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="payment_type"
                                            name="payment_type"
                                            x-model="paymentType"
                                            x-on:change="changePaymentMethod"
                                        >
                                            <x-common.payment-type-options :selectedPaymentType="old('payment_type')" />
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-credit-card"
                                            class="is-large is-left"
                                        />
                                    </x-forms.control>
                                    <x-common.validation-error property="payment_type" />
                                </x-forms.field>
                            </div>
                            <div
                                class="column"
                                x-cloak
                                x-bind:class="{ 'is-hidden': isPaymentNotCredit() }"
                            >
                                <x-forms.label for="cash_received">
                                    Advanced Payment <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.field class="has-addons">
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
                                x-bind:class="{ 'is-hidden': isPaymentNotCredit() }"
                            >
                                <x-forms.field>
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
                                </x-forms.field>
                            </div>
                            <div
                                class="column"
                                x-cloak
                                x-bind:class="{ 'is-hidden': isPaymentInCredit() }"
                            >
                                <x-forms.field>
                                    <x-forms.label for="bank_name">
                                        Bank <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="bank_name"
                                            name="bank_name"
                                            x-model="bankName"
                                        >
                                            <option
                                                selected
                                                value=""
                                            > Select Bank </option>
                                            @if (old('bank_name'))
                                                <option
                                                    value="{{ old('bank_name') }}"
                                                    selected
                                                > {{ old('bank_name') }} </option>
                                            @endif
                                            @include('lists.banks')
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-university"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="bank_name" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div
                                class="column"
                                x-cloak
                                x-bind:class="{ 'is-hidden': isPaymentInCredit() }"
                            >
                                <x-forms.label for="reference_number">
                                    Reference No <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.field>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="reference_number"
                                            name="reference_number"
                                            type="text"
                                            placeholder="Reference No"
                                            x-model="referenceNumber"
                                        />
                                        <x-common.icon
                                            name="fas fa-hashtag"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="reference_number" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        </div>
                    </x-content.footer>
                </x-common.content-wrapper>
            </x-content.main>

            @include('reservations.details-form', ['data' => session()->getOldInput()])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>

    @can('Create Customer')
        <div x-bind:class="Alpine.store('openCreateCustomerModal') ? '' : 'is-hidden'">
            <livewire:create-customer />
        </div>
    @endcan
@endsection

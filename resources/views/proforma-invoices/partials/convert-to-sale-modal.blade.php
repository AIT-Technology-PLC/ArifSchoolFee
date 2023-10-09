<div
    x-data="toggler"
    @open-convert-to-sale-modal.window="toggle"
    class="modal is-active"
    x-cloak
    x-show="!isHidden"
    x-transition
>
    <div
        class="modal-background"
        @click="toggle"
    ></div>
    <div class="modal-content p-lr-20">
        <x-common.content-wrapper>
            <x-content.header title="Issue Invoice" />
            <form
                id="sale"
                action="{{ route('proforma-invoices.convert_to_sale', $proformaInvoice->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    @if (count($errors->get('merchandiseBatches.*')))
                        <div class="box is-shadowless bg-lightpurple text-purple mb-3">
                            @foreach ($errors->get('merchandiseBatches.*') as $errors)
                                @foreach ($errors as $error)
                                    <x-common.icon name="fas fa-times-circle" />
                                    <span> {{ $error }} </span>
                                    <br>
                                @endforeach
                            @endforeach
                        </div>
                    @endif
                    @if (userCompany()->canSaleSubtract())
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-12">
                                <x-forms.field>
                                    <x-forms.label for="warehouse_id">
                                        From <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="warehouse_id"
                                            name="warehouse_id"
                                        >
                                            <option disabled>Select Branch</option>
                                            @foreach (authUser()->getAllowedWarehouses('sale') as $warehouse)
                                                <option
                                                    value="{{ $warehouse->id }}"
                                                    {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}
                                                >{{ $warehouse->name }}</option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-warehouse"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="warehouse_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        </div>
                    @endif
                    <div x-data="cashReceivedType('{{ old('payment_type') }}', '{{ old('cash_received_type') }}', '{{ old('cash_received') }}', '{{ old('due_date') }}', '{{ old('bank_name') }}', '{{ old('reference_number') }}')">
                        <div class="column {{ userCompany()->isDiscountBeforeTax() ? 'is-hidden' : '' }}">
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
                                        class="is-small is-left"
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
                                        class="is-small is-left"
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
                                    Credit Due Date <sup class="has-text-danger"></sup>
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
                </x-content.main>
                <x-content.footer>
                    <x-common.save-button />
                </x-content.footer>
            </form>
        </x-common.content-wrapper>
    </div>
    <x-common.button
        tag="button"
        class="modal-close is-large"
        @click="toggle"
    />
</div>

@extends('layouts.app')

@section('title', 'Create Credit Settlement')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Credit Settlement" />
        <form
            id="formOne"
            action="{{ route('credits.credit-settlements.store', $credit->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div
                    x-data="verifyCashMethod"
                    class="columns is-marginless is-multiline"
                >
                    <div class="column is-6">
                        <x-forms.label for="amount">
                            Amount <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="amount"
                                    name="amount"
                                    type="number"
                                    placeholder="Amount"
                                    value="{{ old('amount') }}"
                                />
                                <x-common.icon
                                    name="fas fa-money-check"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="amount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="method">
                                Method <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="method"
                                    name="method"
                                    @change="changeMethod"
                                    x-init="changeMethod"
                                >
                                    <option
                                        value="Bank Deposit"
                                        {{ old('method') == 'Bank Deposit' ? 'selected' : '' }}
                                    > Bank Deposit </option>
                                    <option
                                        value="Bank Transfer"
                                        {{ old('method') == 'Bank Transfer' ? 'selected' : '' }}
                                    > Bank Transfer </option>
                                    <option
                                        value="Cheque"
                                        {{ old('method') == 'Cheque' ? 'selected' : '' }}
                                    > Cheque </option>
                                    <option
                                        value="Cash"
                                        {{ old('method') == 'Cash' ? 'selected' : '' }}
                                    > Cash </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-hand-holding-usd"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="method" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6 is-hidden"
                        :class="{ 'is-hidden': isMethodCash }"
                    >
                        <x-forms.field>
                            <x-forms.label for="bank_name">
                                Bank <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="bank_name"
                                    name="bank_name"
                                    x-ref="bankName"
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
                        class="column is-6 is-hidden"
                        :class="{ 'is-hidden': isMethodCash }"
                    >
                        <x-forms.label for="reference_number">
                            Reference No <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="reference_number"
                                    name="reference_number"
                                    type="text"
                                    placeholder="Reference No"
                                    value="{{ old('reference_number') ?? '' }}"
                                    x-ref="referenceNumber"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="reference_number" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="settled_at">
                                Settlement Date <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="date"
                                    name="settled_at"
                                    id="settled_at"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('settled_at') ?? now()->toDateString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="settled_at" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="pl-6"
                                    placeholder="Description or note to be taken"
                                >
                                    {{ old('description') ?? '' }}
                                </x-forms.textarea>
                                <x-common.icon
                                    name="fas fa-edit"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="description" />
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
@endsection

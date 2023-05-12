@extends('layouts.app')

@section('title', 'Edit Deposit')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Deposit" />
        <form
            id="formOne"
            action="{{ route('customer-deposits.update', $customerDeposit->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="customer_id">
                                Customer <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="select is-fullwidth has-icons-left">
                                <x-common.customer-list :selected-id="$customerDeposit->customer_id ?? ''" />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
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
                                    value="{{ old('issued_on', $customerDeposit->issued_on->toDateTimeLocalString()) }}"
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
                            <x-forms.label for="deposited_at">
                                Deposited At <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="datetime-local"
                                    name="deposited_at"
                                    id="deposited_at"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('deposited_at', $customerDeposit->deposited_at->toDateTimeLocalString()) }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="deposited_at" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label for="amount">
                            Amount <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="amount"
                                    name="amount"
                                    type="text"
                                    placeholder="Amount"
                                    value="{{ old('amount', $customerDeposit->amount) }}"
                                />
                                <x-common.icon
                                    name="fa-solid fa-sack-dollar"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="amount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="bank_name">
                                Bank <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="bank_name"
                                    name="bank_name"
                                >
                                    <option
                                        selected
                                        value=""
                                    > Select Bank </option>
                                    @if (old('bank_name', $customerDeposit->bank_name))
                                        <option
                                            value="{{ old('bank_name', $customerDeposit->bank_name) }}"
                                            selected
                                        >
                                            {{ old('bank_name', $customerDeposit->bank_name) }}
                                        </option>
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
                    <div class="column is-6">
                        <x-forms.label for="reference_number">
                            Reference No <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    id="reference_number"
                                    name="reference_number"
                                    placeholder="Reference No"
                                    value="{{ old('reference_number', $customerDeposit->reference_number) }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="reference_number" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-data="UploadedFileNameHandler"
                    >
                        <x-forms.field>
                            <x-forms.label for="attachment">
                                Attachment <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <div class="file has-name">
                                <label class="file-label">
                                    <x-forms.input
                                        class="file-input"
                                        type="file"
                                        name="attachment"
                                        x-model="file"
                                        x-on:change="getFileName"
                                    />
                                    <span class="file-cta bg-green has-text-white">
                                        <x-common.icon
                                            name="fas fa-upload"
                                            class="file-icon"
                                        />
                                        <span class="file-label">
                                            Upload Attachment
                                        </span>
                                    </span>
                                    <span
                                        class="file-name"
                                        x-text="fileName || '{{ $customerDeposit->attachment }}' || 'Select File...'"
                                    >
                                    </span>
                                </label>
                            </div>
                            <x-common.validation-error property="attachment" />
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

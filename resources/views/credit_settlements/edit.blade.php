@extends('layouts.app')

@section('title', 'Edit Credit Settlement')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Credit Settlement" />
        <form
            id="formOne"
            action="{{ route('credit-settlements.update', $creditSettlement->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
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
                                    value="{{ $creditSettlement->amount }}"
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
                                >
                                    <option
                                        value="Bank Deposit"
                                        {{ $creditSettlement->method == 'Bank Deposit' ? 'selected' : '' }}
                                    > Bank Deposit </option>
                                    <option
                                        value="Cheque"
                                        {{ $creditSettlement->method == 'Cheque' ? 'selected' : '' }}
                                    > Cheque </option>
                                    <option
                                        value="Cash"
                                        {{ $creditSettlement->method == 'Cash' ? 'selected' : '' }}
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
                    <div class="column is-6">
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
                                    value="{{ $creditSettlement->reference_number ?? '' }}"
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
                                    value="{{ $creditSettlement->settled_at->toDateString() }}"
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
                                    {{ $creditSettlement->description ?? '' }}
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

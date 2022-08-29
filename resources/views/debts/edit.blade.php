@extends('layouts.app')

@section('title', 'Edit Debt')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Debt" />
        <form
            id="formOne"
            action="{{ route('debts.update', $debt->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <x-common.fail-message :message="session('failedMessage')" />
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Debt Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    readonly="{{ !userCompany()->isEditingReferenceNumberEnabled() }}"
                                    value="{{ $debt->code }}"
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
                        <x-forms.label for="debt_amount">
                            Debt Amount <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    id="debt_amount"
                                    name="debt_amount"
                                    type="number"
                                    placeholder="Debt Amount"
                                    value="{{ $debt->debt_amount }}"
                                />
                                <x-common.icon
                                    name="fas fa-money-check"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="debt_amount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="supplier_id">
                                Supplier <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="supplier_id"
                                    name="supplier_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option
                                            value="{{ $supplier->id }}"
                                            @selected($debt->supplier_id == $supplier->id)
                                        >{{ $supplier->company_name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-address-card"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="supplier_id" />
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
                                    value="{{ $debt->issued_on->toDateTimeLocalString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="issued_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="due_date">
                                Due Date <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="date"
                                    name="due_date"
                                    id="due_date"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ $debt->due_date->toDateString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="due_date" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="summernote"
                                    placeholder="Description or note to be taken"
                                >
                                    {{ $debt->description ?? '' }}
                                </x-forms.textarea>
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

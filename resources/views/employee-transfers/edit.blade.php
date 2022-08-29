@extends('layouts.app')

@section('title', 'Edit Employee Transfer')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Transfer" />
        <form
            id="formOne"
            action="{{ route('employee-transfers.update', $employeeTransfer->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Transfer Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    readonly="{{ !userCompany()->isEditingReferenceNumberEnabled() }}"
                                    value="{{ $employeeTransfer->code }}"
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
                            <x-forms.label for="issued_on">
                                Issued On <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="datetime-local"
                                    name="issued_on"
                                    id="issued_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ $employeeTransfer->issued_on->toDateTimeLocalString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="issued_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-content.main>

            @include('employee-transfers.partials.details-form', ['data' => ['employeeTransfer' => old('employeeTransfer') ?? $employeeTransfer->employeeTransferDetails]])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

@extends('layouts.app')

@section('title', 'Edit Warning')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Warning" />
        <form
            id="formOne"
            action="{{ route('warnings.update', $warning->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="employee_id">
                                Employee Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="employee_id"
                                    name="employee_id"
                                >
                                    <option value="{{ $warning->employee_id }}">{{ $warning->employee->user->name }}</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="employee_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="type">
                                Type <sup class="has-text-danger">*</sup>
                                </x-forms.labelfor>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="type"
                                        name="type"
                                    >
                                        <option
                                            value="Initial Warning"
                                            @selected($warning->type == 'Initial Warning')
                                        > Initial Warning </option>
                                        <option
                                            value="Affirmation Warning"
                                            @selected($warning->type == 'Affirmation Warning')
                                        > Affirmation Warning </option>
                                        <option
                                            value="Final Warning"
                                            @selected($warning->type == 'Final Warning')
                                        > Final Warning </option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="type" />
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
                                    value="{{ $warning->issued_on->toDateTimeLocalString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="issued_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="letter">
                                Letter <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.textarea
                                    name="letter"
                                    id="letter"
                                    rows="5"
                                    class="summernote"
                                    placeholder="Description or note to be taken"
                                >{{ $warning->letter ?? '' }}</x-forms.textarea>
                                <x-common.validation-error property="letter" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

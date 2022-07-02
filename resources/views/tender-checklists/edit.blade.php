@extends('layouts.app')

@section('title', 'Update Tender Checklist')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Update Tender Checklist" />
        <form
            id="formOne"
            action="{{ route('tender-checklists.update', $tenderChecklist->id) }}"
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
                            <x-forms.label>
                                Item <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="item"
                                    id="item"
                                    disabled
                                    value="{{ $tenderChecklist->generalTenderChecklist->item }}"
                                />
                                <x-common.icon
                                    name="fas fa-tasks"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="item" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="status">
                                Status <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        type="radio"
                                        name="status"
                                        value=" Completed"
                                        class="mt-3"
                                        @checked($tenderChecklist->status == 'Completed')
                                    >
                                    Completed
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="In Process"
                                        @checked($tenderChecklist->status == 'In Process')
                                    >
                                    In Process
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="Not Started"
                                        @checked($tenderChecklist->status == 'Not Started')
                                    >
                                    Not Started
                                </label>
                                <x-common.validation-error property="status" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="comment">
                                Comment <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="comment"
                                    id="comment"
                                    class="pl-6"
                                    placeholder="Comment or note about this checklist"
                                >
                                    {{ $tenderChecklist->comment }}
                                </x-forms.textarea>
                                <x-common.icon
                                    name="fas fa-edit"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="comment" />
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

@extends('layouts.app')

@section('title', 'Create New General Tender Checklist')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New General Tender Checklist" />
        <form
            id="formOne"
            action="{{ route('general-tender-checklists.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="item">
                                Item <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="item"
                                    name="item"
                                    type="text"
                                    placeholder="Checklist Name"
                                    value="{{ old('item') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-check"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="item" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="tender_checklist_type_id">
                                Checklist Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="tender_checklist_type_id"
                                    name="tender_checklist_type_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Checklist Type</option>
                                    @foreach ($tenderChecklistTypes as $tenderChecklistType)
                                        <option
                                            value="{{ $tenderChecklistType->id }}"
                                            {{ old('tender_checklist_type_id') == $tenderChecklistType->id ? 'selected' : '' }}
                                        >{{ $tenderChecklistType->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-layer-group"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="tender_checklist_type_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="textarea pl-6"
                                    placeholder="Description or note about the new checklist"
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

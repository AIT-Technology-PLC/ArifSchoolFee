@extends('layouts.app')

@section('title', ' Edit Tender Checklist Type')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title=" Edit Tender Checklist Type" />
        <form
            id="formOne"
            action="{{ route('tender-checklist-types.update', $tenderChecklistType->id) }}"
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
                            <x-forms.label for="name">
                                Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    id="name"
                                    name="name"
                                    placeholder="Tender Checklist Type Name"
                                    value="{{ $tenderChecklistType->name }}"
                                />
                                <x-common.icon
                                    name="fas fa-building"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_sensitive">
                                Confidential <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="is_sensitive"
                                    name="is_sensitive"
                                >
                                    <option
                                        value="1"
                                        @selected($tenderChecklistType->isSensitive())
                                    >Yes, confidential</option>
                                    <option
                                        value="0"
                                        @selected(!$tenderChecklistType->isSensitive())
                                    >Not confidential</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-lock"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="is_sensitive" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="pl-6"
                                    placeholder="Description or note about the new checklist"
                                >
                                    {{ $tenderChecklistType->description }}
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

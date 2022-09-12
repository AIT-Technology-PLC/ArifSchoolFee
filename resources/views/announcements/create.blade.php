@extends('layouts.app')

@section('title', 'Create New Announcement')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Announcement" />
        <form
            id="formOne"
            action="{{ route('announcements.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Announcement Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    readonly="{{ !userCompany()->isEditingReferenceNumberEnabled() }}"
                                    value="{{ $currentAnnouncementCode }}"
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
                            <x-forms.label for="title">
                                Title <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="title"
                                    name="title"
                                    type="text"
                                    placeholder="Title"
                                    value="{{ old('title') }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="title" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label for="warehouse_id[]">
                            Branches <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            @foreach ($warehouses as $warehouse)
                                <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                    <input
                                        name="warehouse_id[]"
                                        value="{{ $warehouse->id }}"
                                        type="checkbox"
                                        {{ in_array($warehouse->id, old('warehouse_id', [])) ? 'checked' : '' }}
                                    >
                                    {{ $warehouse->name }}
                                </label>
                                <br>
                            @endforeach
                        </x-forms.field>
                        <x-common.validation-error property="warehouse_id.*" />
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="content">
                                Content <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="content"
                                    id="content"
                                    class="summernote textarea"
                                    placeholder="Description or note to be taken"
                                >{{ old('content') }}
                                </x-forms.textarea>
                                <x-common.validation-error property="content" />
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

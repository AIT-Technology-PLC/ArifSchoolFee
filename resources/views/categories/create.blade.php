@extends('layouts.app')

@section('title', 'Create New Product Category')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Product Category" />
        <form
            id="formOne"
            action="{{ route('categories.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="name">
                                Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="Category Name"
                                    value="{{ old('name') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-layer-group"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
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
                                    rows="10"
                                    class="textarea pl-6"
                                    placeholder="Description or note about the new category"
                                >{{ old('description') ?? '' }}</x-forms.textarea>
                                <x-common.icon
                                    name="fas fa-edit"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
                <div
                    id="newForm"
                    class="columns is-marginless is-multiline is-hidden"
                ></div>
                <div class="columns is-marginless">
                    <div class="column">
                        <x-forms.field>
                            <x-common.button
                                id="addNewForm"
                                tag="button"
                                type="button"
                                mode="button"
                                label="Add More Form"
                                class="bg-purple has-text-white is-small ml-3 mt-6"
                            />
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

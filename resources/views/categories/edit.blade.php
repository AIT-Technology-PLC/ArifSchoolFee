@extends('layouts.app')

@section('title', 'Edit Product Category')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Product Category" />
        <form
            id="formOne"
            action="{{ route('categories.update', $category->id) }}"
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
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="Category Name"
                                    value="{{ $category->name }}"
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
                                >
                                    {{ $category->description }}
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
                @if (!is_null($category->properties))
                    <div class="columns is-marginless is-multiline">
                        @foreach ($category->properties as $property)
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="properties[{{ $loop->index }}][{{ $property['key'] }}]">Property</x-forms.label>
                                    <x-forms.control>
                                        <x-forms.input
                                            id="properties[{{ $loop->index }}][{{ $property['key'] }}]"
                                            name="properties[{{ $loop->index }}][key]"
                                            type="text"
                                            value="{{ $property['key'] }}"
                                        />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="properties[{{ $loop->index }}][{{ $property['value'] }}]">Data</x-forms.label>
                                    <x-forms.control>
                                        <x-forms.input
                                            id="properties[{{ $loop->index }}][{{ $property['value'] }}]"
                                            name="properties[{{ $loop->index }}][value]"
                                            type="text"
                                            value="{{ $property['value'] }}"
                                        />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

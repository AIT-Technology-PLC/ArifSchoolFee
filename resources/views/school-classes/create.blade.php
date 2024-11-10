@extends('layouts.app')

@section('title', 'Create New Class')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-blue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        New Class
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('school-classes.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <x-common.fail-message :message="session('limitReachedMessage')" />
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="name">
                                Name<sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="name"
                                    id="name"
                                    placeholder="Class Name"
                                    value="{{ old('name') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label for="section_id[]">
                            Section <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            @foreach ($sections as $section)
                                <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                    <input
                                        name="section_id[]"
                                        value="{{ $section->id }}"
                                        type="checkbox"
                                        {{ in_array($section->id, old('section_id', [])) ? 'checked' : '' }}
                                    >
                                    {{ $section->name }}
                                </label>
                                <br>
                            @endforeach
                        </x-forms.field>
                        <x-common.validation-error property="section_id.*" />
                    </div>
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

@extends('layouts.app')

@section('title', 'Create New Notice')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        New Notice
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('notices.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline is-mobile">
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
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
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.field>
                            <x-forms.label for="notice_date">
                                Date <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left is-fullwidth">
                                <x-forms.input
                                    class="is-fullwidth"
                                    id="notice_date"
                                    name="notice_date"
                                    type="date"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('notice_date') ?? now()->toDateString()  }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="notice_date" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.label for="warehouse_id[]">
                            Branches <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            @foreach ($branches as $branch)
                                <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                    <input
                                        name="warehouse_id[]"
                                        value="{{ $branch->id }}"
                                        type="checkbox"
                                        {{ in_array($branch->id, old('warehouse_id', [])) ? 'checked' : '' }}
                                    >
                                    {{ $branch->name }}
                                </label>
                                <br>
                            @endforeach
                        </x-forms.field>
                        <x-common.validation-error property="warehouse_id.*" />
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.label for="type[]">
                            To <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            @foreach ($roles as $role)
                                <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                    <input
                                        name="type[]"
                                        value="{{ $role->name }}"
                                        type="checkbox"
                                        {{ in_array($role->id, old('type', [])) ? 'checked' : '' }}
                                    >
                                    {{ $role->name }}
                                </label>
                                <br>
                            @endforeach
                        </x-forms.field>
                        <x-common.validation-error property="type.*" />
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="message">
                                Notice <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="message"
                                    id="message"
                                    class="summernote textarea"
                                    placeholder="Notice Description or Note"
                                >{{ old('message') }}
                                </x-forms.textarea>
                                <x-common.validation-error property="message" />
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

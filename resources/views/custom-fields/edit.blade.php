@extends('layouts.app')

@section('title', 'Edit Custom Field')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Custom Field" />
        <form
            id="formOne"
            action="{{ route('custom-fields.update', $customField->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @method('PATCH')
            @csrf
            <x-content.main>
                <div class="mx-3">
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-4">
                            <x-forms.label for="label">
                                Label <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="text"
                                        id="label"
                                        name="label"
                                        value="{{ old('label', $customField->label) }}"
                                    />
                                    <x-common.icon
                                        name="fa-solid fa-table"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="label" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.label for="options">
                                Options <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="text"
                                        id="options"
                                        name="options"
                                        value="{{ old('options', $customField->options) }}"
                                    />
                                    <x-common.icon
                                        name="fa-solid fa-list"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="options" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.label for="model_type">
                                Model Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="text"
                                        id="model_type"
                                        name="model_type"
                                        value="{{ old('model_type', $customField->model_type) }}"
                                    />
                                    <x-common.icon
                                        name="fa-solid fa-diagram-project"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="model_type" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.label for="order">
                                Order <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="text"
                                        id="order"
                                        name="order"
                                        value="{{ old('order', $customField->order) }}"
                                    />
                                    <x-common.icon
                                        name="fa-solid fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="order" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.label for="icon">
                                Icon <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="text"
                                        id="icon"
                                        name="icon"
                                        value="{{ old('icon', $customField->icon) }}"
                                    />
                                    <x-common.icon
                                        name="fa-solid fa-icons"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="icon" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.label for="placeholder">
                                Placeholder <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="text"
                                        id="placeholder"
                                        name="placeholder"
                                        value="{{ old('placeholder', $customField->placeholder) }}"
                                    />
                                    <x-common.icon
                                        name="fa-solid fa-text-width"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="placeholder" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.label for="default_value">
                                Default Value <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="text"
                                        id="default_value"
                                        name="default_value"
                                        value="{{ old('default_value', $customField->default_value) }}"
                                    />
                                    <x-common.icon
                                        name="fa-solid fa-info"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="default_value" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.label for="column_size">
                                Column Size <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="text"
                                        id="column_size"
                                        name="column_size"
                                        value="{{ old('column_size', $customField->column_size) }}"
                                    />
                                    <x-common.icon
                                        name="fa-solid fa-ruler-horizontal"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="column_size" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.field>
                                <x-forms.label>
                                    Enabled <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.label class="radio is-inline">
                                        <input
                                            type="radio"
                                            name="is_active"
                                            id="is_active"
                                            value="1"
                                            @checked(old('is_active', $customField->is_active) == 1)
                                        />
                                        Yes
                                    </x-forms.label>
                                    <x-forms.label class="radio is-inline">
                                        <input
                                            type="radio"
                                            name="is_active"
                                            id="is_active"
                                            value="0"
                                            @checked(old('is_active', $customField->is_active) == 0)
                                        />
                                        No
                                    </x-forms.label>
                                    <x-common.validation-error property="is_active" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.field>
                                <x-forms.label>
                                    Required <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.label class="radio is-inline">
                                        <input
                                            type="radio"
                                            name="is_required"
                                            id="is_required"
                                            value="1"
                                            @checked(old('is_required', $customField->is_required) == 1)
                                        />
                                        Yes
                                    </x-forms.label>
                                    <x-forms.label class="radio is-inline">
                                        <input
                                            type="radio"
                                            name="is_required"
                                            id="is_required"
                                            value="0"
                                            @checked(old('is_required', $customField->is_required) == 0)
                                        />
                                        No
                                    </x-forms.label>
                                    <x-common.validation-error property="is_required" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.field>
                                <x-forms.label>
                                    Unique <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.label class="radio is-inline">
                                        <input
                                            type="radio"
                                            name="is_unique"
                                            id="is_unique"
                                            value="1"
                                            @checked(old('is_unique', $customField->is_unique) == 1)
                                        />
                                        Yes
                                    </x-forms.label>
                                    <x-forms.label class="radio is-inline">
                                        <input
                                            type="radio"
                                            name="is_unique"
                                            id="is_unique"
                                            value="0"
                                            @checked(old('is_unique', $customField->is_unique) == 0)
                                        />
                                        No
                                    </x-forms.label>
                                    <x-common.validation-error property="is_unique" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.field>
                                <x-forms.label>
                                    Column Visibility <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.label class="radio is-inline">
                                        <input
                                            type="radio"
                                            name="is_visible"
                                            id="is_visible"
                                            value="1"
                                            @checked(old('is_visible', $customField->is_visible) == 1)
                                        />
                                        Yes
                                    </x-forms.label>
                                    <x-forms.label class="radio is-inline">
                                        <input
                                            type="radio"
                                            name="is_visible"
                                            id="is_visible"
                                            value="0"
                                            @checked(old('is_visible', $customField->is_visible) == 0)
                                        />
                                        No
                                    </x-forms.label>
                                    <x-common.validation-error property="is_visible" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.field>
                                <x-forms.label>
                                    Print <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.label class="radio is-inline">
                                        <input
                                            type="radio"
                                            name="is_printable"
                                            id="is_printable"
                                            value="1"
                                            @checked(old('is_printable', $customField->is_printable) == 1)
                                        />
                                        Yes
                                    </x-forms.label>
                                    <x-forms.label class="radio is-inline">
                                        <input
                                            type="radio"
                                            name="is_printable"
                                            id="is_printable"
                                            value="0"
                                            @checked(old('is_printable', $customField->is_printable) == 0)
                                        />
                                        No
                                    </x-forms.label>
                                    <x-common.validation-error property="is_printable" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    </div>
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

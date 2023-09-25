@extends('layouts.app')

@section('title', 'Create Pad')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Pad" />
        <form
            id="formOne"
            action="{{ route('pads.store') }}"
            method="POST"
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
                                    type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name') }}"
                                />
                                <x-common.icon
                                    name="fas fa-book"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="abbreviation">
                                Abbreviation <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="abbreviation"
                                    id="abbreviation"
                                    value="{{ old('abbreviation') }}"
                                />
                                <x-common.icon
                                    name="fas fa-font"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="abbreviation" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label for="icon">
                            Icon <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="icon"
                                    id="icon"
                                    value="{{ old('icon') }}"
                                />
                                <x-common.icon
                                    name="fas fa-icons"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                            <x-common.validation-error property="icon" />
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="inventory_operation_type">
                                Inventory Operation Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="inventory_operation_type"
                                    name="inventory_operation_type"
                                >
                                    @foreach (App\Models\Pad::INVENTORY_OPERATIONS as $operation)
                                        <option
                                            value="{{ $operation }}"
                                            @selected(old('inventory_operation_type') == $operation)
                                        >
                                            {{ str()->ucfirst($operation) }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-cog"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="inventory_operation_type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Print Paper Orientation & Size <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="print_orientation"
                                    name="print_orientation"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Orientation
                                    </option>
                                    <option
                                        value="portrait"
                                        @selected(old('print_orientation') == 'portrait' || is_null(old('print_orientation')))
                                    >
                                        Portrait
                                    </option>
                                    <option
                                        value="landscape"
                                        @selected(old('print_orientation') == 'landscape')
                                    >
                                        Landscape
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-print"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="print_orientation" />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="print_paper_size"
                                    name="print_paper_size"
                                    type="text"
                                    placeholder="Page Size"
                                    value="{{ old('print_paper_size') }}"
                                    autocomplete="print_paper_size"
                                />
                                <x-common.icon
                                    name="fas fa-print"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="print_paper_size" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="module">
                                Module <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="module"
                                    name="module"
                                >
                                    @foreach (App\Models\Pad::MODULES as $module)
                                        <option
                                            value="{{ $module }}"
                                            @selected(old('module') == $module)
                                        >
                                            {{ $module }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="module" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="convert_to">
                                Convert To <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth is-multiple"
                                    id="convert_to"
                                    name="convert_to[]"
                                    multiple
                                    size="2"
                                >
                                    @foreach ($features as $feature)
                                        <option
                                            value="{{ $feature }}"
                                            @selected(in_array($feature, old('convert_to', [])))
                                        >
                                            {{ str($feature)->title()->singular() }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="convert_to" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="convert_from">
                                Convert From <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth is-multiple"
                                    id="convert_from"
                                    name="convert_from[]"
                                    multiple
                                    size="2"
                                >
                                    @foreach ($features as $feature)
                                        <option
                                            value="{{ $feature }}"
                                            @selected(in_array($feature, old('convert_from', [])))
                                        >
                                            {{ str($feature)->title()->singular() }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="convert_from" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label>
                                Approvable <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="is_approvable"
                                        id="is_approvable"
                                        value="1"
                                        @checked(old('is_approvable') == 1)
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="is_approvable"
                                        id="is_approvable"
                                        value="0"
                                        @checked(old('is_approvable') == 0)
                                    />
                                    No
                                </x-forms.label>
                                <x-common.validation-error property="is_approvable" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label>
                                Printable <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="is_printable"
                                        id="is_printable"
                                        value="1"
                                        @checked(old('is_printable') == 1)
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="is_printable"
                                        id="is_printable"
                                        value="0"
                                        @checked(old('is_printable') == 0)
                                    />
                                    No
                                </x-forms.label>
                                <x-common.validation-error property="is_printable" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label>
                                Prices <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="has_prices"
                                        id="has_prices"
                                        value="1"
                                        @checked(old('has_prices') == 1)
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="has_prices"
                                        id="has_prices"
                                        value="0"
                                        @checked(old('has_prices') == 0)
                                    />
                                    No
                                </x-forms.label>
                                <x-common.validation-error property="has_prices" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label>
                                Payment Terms <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="has_payment_term"
                                        id="has_payment_term"
                                        value="1"
                                        @checked(old('has_payment_term') == 1)
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="has_payment_term"
                                        id="has_payment_term"
                                        value="0"
                                        @checked(old('has_payment_term') == 0)
                                    />
                                    No
                                </x-forms.label>
                                <x-common.validation-error property="has_payment_term" />
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
                                        name="is_enabled"
                                        id="is_enabled"
                                        value="1"
                                        @checked(old('is_enabled') == 1)
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="is_enabled"
                                        id="is_enabled"
                                        value="0"
                                        @checked(old('is_enabled') == 0)
                                    />
                                    No
                                </x-forms.label>
                                <x-common.validation-error property="is_enabled" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>

                @include('pads.partials.statuses', ['data' => session()->getOldInput()])

                <div
                    x-data="padMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                    x-init="setErrors({{ Js::from($errors->get('field.*')) }})"
                >
                    <template
                        x-for="(field, index) in fields"
                        x-bind:key="index"
                    >
                        <div class="mx-3">
                            <x-forms.field class="has-addons mb-0 mt-5">
                                <x-forms.control>
                                    <span
                                        class="tag bg-green has-text-white is-medium is-radiusless"
                                        x-text="`Item ${index + 1}`"
                                    ></span>
                                </x-forms.control>
                                <x-forms.control>
                                    <x-common.button
                                        tag="button"
                                        mode="tag"
                                        type="button"
                                        class="bg-lightgreen has-text-white is-medium is-radiusless"
                                        x-on:click="remove(index)"
                                    >
                                        <x-common.icon
                                            name="fas fa-times-circle"
                                            class="text-green"
                                        />
                                    </x-common.button>
                                </x-forms.control>
                            </x-forms.field>
                            <div class="box has-background-white-bis radius-top-0">
                                <div class="columns is-marginless is-multiline">
                                    <div class="column is-12 has-text-centered">
                                        <x-forms.field>
                                            <x-forms.label>
                                                Relational Field <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_relational_field]`"
                                                        x-bind:id="`field[${index}][is_relational_field]`"
                                                        value="1"
                                                        x-model="field.is_relational_field"
                                                    />
                                                    Yes
                                                </x-forms.label>
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_relational_field]`"
                                                        x-bind:id="`field[${index}][is_relational_field]`"
                                                        value="0"
                                                        x-model="field.is_relational_field"
                                                    />
                                                    No
                                                </x-forms.label>
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="errors[`field.${index}.is_relational_field`]"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div
                                        x-show="isFieldRelational(field.is_relational_field)"
                                        class="column is-4"
                                    >
                                        <x-forms.field>
                                            <x-forms.label x-bind:for="`field[${index}][relationship_type]`">
                                                Relationship Type <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.select
                                                    class="is-fullwidth"
                                                    x-bind:id="`field[${index}][relationship_type]`"
                                                    x-bind:name="`field[${index}][relationship_type]`"
                                                    x-model="field.relationship_type"
                                                >
                                                    @foreach (App\Models\Pad::RELATIONSHIP_TYPES as $relationshipType)
                                                        <option value="{{ $relationshipType }}">
                                                            {{ $relationshipType }}
                                                        </option>
                                                    @endforeach
                                                </x-forms.select>
                                                <x-common.icon
                                                    name="fas fa-exchange-alt"
                                                    class="is-small is-left"
                                                />
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="errors[`field.${index}.relationship_type`]"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div
                                        x-show="isFieldRelational(field.is_relational_field)"
                                        class="column is-4"
                                    >
                                        <x-forms.label for="`field[${index}][model_name]`">
                                            Model Name <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    x-bind:name="`field[${index}][model_name]`"
                                                    x-bind:id="`field[${index}][model_name]`"
                                                    x-model="field.model_name"
                                                />
                                                <x-common.icon
                                                    name="fas fa-square"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`field.${index}.model_name`]"
                                            ></span>
                                        </x-forms.field>
                                    </div>
                                    <div
                                        x-show="isFieldRelational(field.is_relational_field)"
                                        class="column is-4"
                                    >
                                        <x-forms.label for="`field[${index}][representative_column]`">
                                            Representative Column <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    x-bind:name="`field[${index}][representative_column]`"
                                                    x-bind:id="`field[${index}][representative_column]`"
                                                    x-model="field.representative_column"
                                                />
                                                <x-common.icon
                                                    name="fas fa-square"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`field.${index}.representative_column`]"
                                            ></span>
                                        </x-forms.field>
                                    </div>
                                    <div
                                        x-show="isFieldRelational(field.is_relational_field)"
                                        class="column is-4"
                                    >
                                        <x-forms.label for="`field[${index}][component_name]`">
                                            Component <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    x-bind:name="`field[${index}][component_name]`"
                                                    x-bind:id="`field[${index}][component_name]`"
                                                    x-model="field.component_name"
                                                />
                                                <x-common.icon
                                                    name="fas fa-key"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`field.${index}.component_name`]"
                                            ></span>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-4">
                                        <x-forms.label for="`field[${index}][label]`">
                                            Label <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    x-bind:name="`field[${index}][label]`"
                                                    x-bind:id="`field[${index}][label]`"
                                                    x-model="field.label"
                                                />
                                                <x-common.icon
                                                    name="fas fa-font"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`field.${index}.label`]"
                                            ></span>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-4">
                                        <x-forms.label for="`field[${index}][icon]`">
                                            Icon <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    x-bind:name="`field[${index}][icon]`"
                                                    x-bind:id="`field[${index}][icon]`"
                                                    x-model="field.icon"
                                                />
                                                <x-common.icon
                                                    name="fas fa-icons"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`field.${index}.icon`]"
                                            ></span>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-4">
                                        <x-forms.label for="`field[${index}][tag]`">
                                            Tag <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field x-bind:class="{ 'has-addons': isTagInput(field.tag) }">
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    x-bind:name="`field[${index}][tag]`"
                                                    x-bind:id="`field[${index}][tag]`"
                                                    x-model="field.tag"
                                                />
                                                <x-common.icon
                                                    name="fas fa-code"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                            <x-forms.control
                                                x-show="isTagInput(field.tag)"
                                                class="has-icons-left"
                                            >
                                                <x-forms.input
                                                    type="text"
                                                    placeholder="Input Type (e.g. text, number ...)"
                                                    x-bind:name="`field[${index}][tag_type]`"
                                                    x-bind:id="`field[${index}][tag_type]`"
                                                    x-model="field.tag_type"
                                                />
                                                <x-common.icon
                                                    name="fas fa-code"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`field.${index}.tag`]"
                                            ></span>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`field.${index}.tag_type`]"
                                            ></span>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-3">
                                        <x-forms.field>
                                            <x-forms.label>
                                                Master Field <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_master_field]`"
                                                        x-bind:id="`field[${index}][is_master_field]`"
                                                        value="1"
                                                        x-model="field.is_master_field"
                                                    />
                                                    Yes
                                                </x-forms.label>
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_master_field]`"
                                                        x-bind:id="`field[${index}][is_master_field]`"
                                                        value="0"
                                                        x-model="field.is_master_field"
                                                    />
                                                    No
                                                </x-forms.label>
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="errors[`field.${index}.is_master_field`]"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-3">
                                        <x-forms.field>
                                            <x-forms.label>
                                                Required <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_required]`"
                                                        x-bind:id="`field[${index}][is_required]`"
                                                        value="1"
                                                        x-model="field.is_required"
                                                    />
                                                    Yes
                                                </x-forms.label>
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_required]`"
                                                        x-bind:id="`field[${index}][is_required]`"
                                                        value="0"
                                                        x-model="field.is_required"
                                                    />
                                                    No
                                                </x-forms.label>
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="errors[`field.${index}.is_required`]"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-3">
                                        <x-forms.field>
                                            <x-forms.label>
                                                Column Visibility <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_visible]`"
                                                        x-bind:id="`field[${index}][is_visible]`"
                                                        value="1"
                                                        x-model="field.is_visible"
                                                    />
                                                    Yes
                                                </x-forms.label>
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_visible]`"
                                                        x-bind:id="`field[${index}][is_visible]`"
                                                        value="0"
                                                        x-model="field.is_visible"
                                                    />
                                                    No
                                                </x-forms.label>
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="errors[`field.${index}.is_visible`]"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-3">
                                        <x-forms.field>
                                            <x-forms.label>
                                                Printable <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_printable]`"
                                                        x-bind:id="`field[${index}][is_printable]`"
                                                        value="1"
                                                        x-model="field.is_printable"
                                                    />
                                                    Yes
                                                </x-forms.label>
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_printable]`"
                                                        x-bind:id="`field[${index}][is_printable]`"
                                                        value="0"
                                                        x-model="field.is_printable"
                                                    />
                                                    No
                                                </x-forms.label>
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="errors[`field.${index}.is_printable`]"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-3">
                                        <x-forms.field>
                                            <x-forms.label>
                                                Readonly <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_readonly]`"
                                                        x-bind:id="`field[${index}][is_readonly]`"
                                                        value="1"
                                                        x-model="field.is_readonly"
                                                    />
                                                    Yes
                                                </x-forms.label>
                                                <x-forms.label class="radio is-inline">
                                                    <input
                                                        type="radio"
                                                        x-bind:name="`field[${index}][is_readonly]`"
                                                        x-bind:id="`field[${index}][is_readonly]`"
                                                        value="0"
                                                        x-model="field.is_readonly"
                                                    />
                                                    No
                                                </x-forms.label>
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="errors[`field.${index}.is_readonly`]"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <x-common.button
                        tag="button"
                        type="button"
                        mode="button"
                        label="Add Field"
                        icon="fas fa-plus-circle"
                        class="bg-green has-text-white is-small ml-3 mt-6"
                        x-on:click="add"
                    />
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("padMasterDetailForm", ({
                field
            }) => ({
                fields: [],
                errors: {},

                init() {
                    if (field) {
                        this.fields = field;
                        return;
                    }
                },
                setErrors(errors) {
                    this.errors = errors;
                },
                add() {
                    this.fields.push({
                        relationship_type: "",
                        model_name: "",
                        representative_column: "",
                        component_name: "",
                        label: "",
                        icon: "",
                        is_relational_field: "0",
                        is_master_field: "0",
                        is_required: "0",
                        is_visible: "0",
                        is_printable: "0",
                        is_readonly: "0",
                        tag: "",
                        tag_type: "",
                    });
                },
                remove(index) {
                    this.fields.splice(index, 1);
                },
                isFieldRelational(fieldType) {
                    return fieldType === "1";
                },
                isTagInput(tagName) {
                    return tagName.toLowerCase() === "input" || tagName.toLowerCase() === "select";
                },
            }));
        });
    </script>
@endpush

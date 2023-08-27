@extends('layouts.app')

@section('title', 'Edit Pad')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Pad" />
        <form
            id="formOne"
            action="{{ route('pads.update', $pad->id) }}"
            method="POST"
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
                                    name="name"
                                    id="name"
                                    value="{{ $pad->name }}"
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
                                    value="{{ $pad->abbreviation }}"
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
                                    value="{{ $pad->icon }}"
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
                                            @selected($pad->inventory_operation_type == $operation)
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
                            <x-forms.control class="has-icons-left">
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
                                        @selected(old('print_orientation', $pad->print_orientation) == 'portrait')
                                    >
                                        Portrait
                                    </option>
                                    <option
                                        value="landscape"
                                        @selected(old('print_orientation', $pad->print_orientation) == 'landscape')
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
                                    value="{{ old('print_paper_size', $pad->print_paper_size) }}"
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
                                            @selected($pad->module == $module)
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
                                Convert To<sup class="has-text-danger"></sup>
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
                                            @selected(in_array($feature, $pad->convert_to))
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
                                Convert From<sup class="has-text-danger"></sup>
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
                                            @selected(in_array($feature, $pad->convert_from))
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
                                        @checked($pad->is_approvable)
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="is_approvable"
                                        id="is_approvable"
                                        value="0"
                                        @checked(!$pad->is_approvable)
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
                                        @checked($pad->is_printable)
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="is_printable"
                                        id="is_printable"
                                        value="0"
                                        @checked(!$pad->is_printable)
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
                                        @checked($pad->has_prices)
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="has_prices"
                                        id="has_prices"
                                        value="0"
                                        @checked(!$pad->has_prices)
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
                                        @checked($pad->has_payment_term)
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="has_payment_term"
                                        id="has_payment_term"
                                        value="0"
                                        @checked(!$pad->has_payment_term)
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
                                        @checked($pad->is_enabled)
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        name="is_enabled"
                                        id="is_enabled"
                                        value="0"
                                        @checked(!$pad->is_enabled)
                                    />
                                    No
                                </x-forms.label>
                                <x-common.validation-error property="is_enabled" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>

                @include('pads.partials.statuses', ['data' => ['status' => old('status', $pad->padStatuses)]])

                @foreach ($pad->padFields as $padField)
                    <div class="mx-3">
                        <x-forms.field class="has-addons mb-0 mt-5">
                            <x-forms.control>
                                <span class="tag bg-green has-text-white is-medium is-radiusless">
                                    Item {{ $loop->iteration }}
                                </span>
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
                                                    name="field[{{ $loop->index }}][is_relational_field]"
                                                    id="field[{{ $loop->index }}][is_relational_field]"
                                                    value="1"
                                                    @checked($padField->padRelation)
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    name="field[{{ $loop->index }}][is_relational_field]"
                                                    id="field[{{ $loop->index }}][is_relational_field]"
                                                    value="0"
                                                    @checked(!$padField->padRelation)
                                                />
                                                No
                                            </x-forms.label>
                                            <x-common.validation-error property="field.{{ $loop->index }}.is_relational_field" />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                @if ($padField->padRelation)
                                    <div class="column is-4">
                                        <x-forms.field>
                                            <x-forms.label for="field[{{ $loop->index }}][relationship_type]">
                                                Relationship Type <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.select
                                                    class="is-fullwidth"
                                                    id="field[{{ $loop->index }}][relationship_type]"
                                                    name="field[{{ $loop->index }}][relationship_type]"
                                                >
                                                    @foreach (App\Models\Pad::RELATIONSHIP_TYPES as $relationshipType)
                                                        <option
                                                            value="{{ $relationshipType }}"
                                                            @selected($padField->padRelation?->relationship_type == $relationshipType)
                                                        >
                                                            {{ $relationshipType }}
                                                        </option>
                                                    @endforeach
                                                </x-forms.select>
                                                <x-common.icon
                                                    name="fas fa-exchange-alt"
                                                    class="is-small is-left"
                                                />
                                                <x-common.validation-error property="field.{{ $loop->index }}.relationship_type" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-4">
                                        <x-forms.label for="field[{{ $loop->index }}][model_name]">
                                            Model Name <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    name="field[{{ $loop->index }}][model_name]"
                                                    id="field[{{ $loop->index }}][model_name]"
                                                    value="{{ $padField->padRelation->model_name }}"
                                                />
                                                <x-common.icon
                                                    name="fas fa-square"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                            <x-common.validation-error property="field.{{ $loop->index }}.model_name" />
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-4">
                                        <x-forms.label for="field[{{ $loop->index }}][representative_column]">
                                            Representative Column <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    name="field[{{ $loop->index }}][representative_column]"
                                                    id="field[{{ $loop->index }}][representative_column]"
                                                    value="{{ $padField->padRelation->representative_column }}"
                                                />
                                                <x-common.icon
                                                    name="fas fa-square"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                            <x-common.validation-error property="field.{{ $loop->index }}.representative_column" />
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-4">
                                        <x-forms.label for="field[{{ $loop->index }}][component_name]">
                                            Component <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    name="field[{{ $loop->index }}][component_name]"
                                                    id="field[{{ $loop->index }}][component_name]"
                                                    value="{{ $padField->padRelation->component_name }}"
                                                />
                                                <x-common.icon
                                                    name="fas fa-key"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                            <x-common.validation-error property="field.{{ $loop->index }}.component_name" />
                                        </x-forms.field>
                                    </div>
                                @endif
                                <div class="column is-4">
                                    <x-forms.label for="field[{{ $loop->index }}][label]">
                                        Label <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                type="text"
                                                name="field[{{ $loop->index }}][label]"
                                                id="field[{{ $loop->index }}][label]"
                                                value="{{ $padField->label }}"
                                            />
                                            <x-common.icon
                                                name="fas fa-font"
                                                class="is-large is-left"
                                            />
                                        </x-forms.control>
                                        <x-common.validation-error property="field.{{ $loop->index }}.label" />
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.label for="field[{{ $loop->index }}][icon]">
                                        Icon <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                type="text"
                                                name="field[{{ $loop->index }}][icon]"
                                                id="field[{{ $loop->index }}][icon]"
                                                value="{{ $padField->icon }}"
                                            />
                                            <x-common.icon
                                                name="fas fa-icons"
                                                class="is-large is-left"
                                            />
                                        </x-forms.control>
                                        <x-common.validation-error property="field.{{ $loop->index }}.icon" />
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.label for="field[{{ $loop->index }}][tag]">
                                        Tag <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                type="text"
                                                name="field[{{ $loop->index }}][tag]"
                                                id="field[{{ $loop->index }}][tag]"
                                                value="{{ $padField->tag }}"
                                            />
                                            <x-common.icon
                                                name="fas fa-code"
                                                class="is-large is-left"
                                            />
                                        </x-forms.control>
                                        @if ($padField->tag == 'input')
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    placeholder="Input Type (e.g. text, number ...)"
                                                    name="field[{{ $loop->index }}][tag_type]"
                                                    id="field[{{ $loop->index }}][tag_type]"
                                                    value="{{ $padField->tag_type }}"
                                                />
                                                <x-common.icon
                                                    name="fas fa-code"
                                                    class="is-large is-left"
                                                />
                                            </x-forms.control>
                                        @endif
                                        <x-common.validation-error property="field.{{ $loop->index }}.tag" />
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
                                                    name="field[{{ $loop->index }}][is_master_field]"
                                                    id="field[{{ $loop->index }}][is_master_field]"
                                                    value="1"
                                                    @checked($padField->is_master_field)
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    name="field[{{ $loop->index }}][is_master_field]"
                                                    id="field[{{ $loop->index }}][is_master_field]"
                                                    value="0"
                                                    @checked(!$padField->is_master_field)
                                                />
                                                No
                                            </x-forms.label>
                                            <x-common.validation-error property="field.{{ $loop->index }}.is_master_field" />
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
                                                    name="field[{{ $loop->index }}][is_required]"
                                                    id="field[{{ $loop->index }}][is_required]"
                                                    value="1"
                                                    @checked($padField->is_required)
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    name="field[{{ $loop->index }}][is_required]"
                                                    id="field[{{ $loop->index }}][is_required]"
                                                    value="0"
                                                    @checked(!$padField->is_required)
                                                />
                                                No
                                            </x-forms.label>
                                            <x-common.validation-error property="field.{{ $loop->index }}.is_required" />
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
                                                    name="field[{{ $loop->index }}][is_visible]"
                                                    id="field[{{ $loop->index }}][is_visible]"
                                                    value="1"
                                                    @checked($padField->is_visible)
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    name="field[{{ $loop->index }}][is_visible]"
                                                    id="field[{{ $loop->index }}][is_visible]"
                                                    value="0"
                                                    @checked(!$padField->is_visible)
                                                />
                                                No
                                            </x-forms.label>
                                            <x-common.validation-error property="field.{{ $loop->index }}.is_visible" />
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
                                                    name="field[{{ $loop->index }}][is_printable]"
                                                    id="field[{{ $loop->index }}][is_printable]"
                                                    value="1"
                                                    @checked($padField->is_printable)
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    name="field[{{ $loop->index }}][is_printable]"
                                                    id="field[{{ $loop->index }}][is_printable]"
                                                    value="0"
                                                    @checked(!$padField->is_printable)
                                                />
                                                No
                                            </x-forms.label>
                                            <x-common.validation-error property="field.{{ $loop->index }}.is_printable" />
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
                                                    name="field[{{ $loop->index }}][is_readonly]"
                                                    id="field[{{ $loop->index }}][is_readonly]"
                                                    value="1"
                                                    @checked($padField->is_readonly)
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    name="field[{{ $loop->index }}][is_readonly]"
                                                    id="field[{{ $loop->index }}][is_readonly]"
                                                    value="0"
                                                    @checked(!$padField->is_readonly)
                                                />
                                                No
                                            </x-forms.label>
                                            <x-common.validation-error property="field.{{ $loop->index }}.is_readonly" />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

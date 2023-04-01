@extends('layouts.app')

@section('title', 'Edit Compensation')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Compensation" />
        <form
            id="formOne"
            action="{{ route('compensations.update', $compensation->id) }}"
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
                                    placeholder="Name"
                                    value="{{ old('name', $compensation->name) }}"
                                />
                                <x-common.icon
                                    name="fa-solid fa-circle-dollar-to-slot"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="type">
                                Type <sup class="has-text-danger">*</sup>
                                </x-forms.labelfor>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="type"
                                        name="type"
                                    >
                                        <option
                                            value="earning"
                                            @selected(old('type', $compensation->type) == 'earning')
                                        > Earning </option>
                                        <option
                                            value="deduction"
                                            @selected(old('type', $compensation->type) == 'deduction')
                                        > Deduction </option>
                                        <option
                                            value="none"
                                            @selected(old('type', $compensation->type) == 'none')
                                        > None </option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="type" />
                                </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="depends_on">
                                Depends On <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="depends_on"
                                    name="depends_on"
                                >
                                    <option disabled>Select Compensation</option>
                                    @foreach ($compensations as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            @selected(old('depends_on', $compensation->depends_on) == $item->id)
                                        >{{ $item->name }}</option>
                                    @endforeach
                                    <option
                                        value=""
                                        @selected(is_null(old('depends_on', $compensation->depends_on)))
                                    >None</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="depends_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="percentage">
                                Percentage <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="percentage"
                                    name="percentage"
                                    type="number"
                                    placeholder="Percentage"
                                    value="{{ old('percentage', $compensation->percentage) }}"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="percentage" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="default_value">
                                Default Value <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="default_value"
                                    name="default_value"
                                    type="number"
                                    placeholder="Default Value"
                                    value="{{ old('default_value', $compensation->default_value) }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="default_value" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="maximum_amount">
                                Maximum Amount <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="maximum_amount"
                                    name="maximum_amount"
                                    type="number"
                                    placeholder="Maximum Amount"
                                    value="{{ old('maximum_amount', $compensation->maximum_amount) }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="maximum_amount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_active">
                                Active or not <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        id="is_active"
                                        name="is_active"
                                        type="radio"
                                        value="1"
                                        class="mt-3"
                                        @checked($compensation->isActive())
                                    >
                                    Active
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        id="is_active"
                                        type="radio"
                                        name="is_active"
                                        value="0"
                                        @checked(!$compensation->isActive())
                                    >
                                    Not Active
                                </label>
                                <x-common.validation-error property="is_active" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="has_formula">
                                Formula or Amount <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        id="has_formula"
                                        name="has_formula"
                                        type="radio"
                                        value="1"
                                        class="mt-3"
                                        @checked($compensation->hasFormula())
                                    >
                                    Formula-based
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        id="has_formula"
                                        type="radio"
                                        name="has_formula"
                                        value="0"
                                        @checked(!$compensation->hasFormula())
                                    >
                                    Amount-based
                                </label>
                                <x-common.validation-error property="has_formula" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_taxable">
                                Taxable or not <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        id="is_taxable"
                                        name="is_taxable"
                                        type="radio"
                                        value="1"
                                        class="mt-3"
                                        @checked($compensation->isTaxable())
                                    >
                                    Taxable
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        id="is_taxable"
                                        type="radio"
                                        name="is_taxable"
                                        value="0"
                                        @checked(!$compensation->isTaxable())
                                    >
                                    Not Taxable
                                </label>
                                <x-common.validation-error property="is_taxable" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_adjustable">
                                Adjustable or not <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        id="is_adjustable"
                                        name="is_adjustable"
                                        type="radio"
                                        value="1"
                                        class="mt-3"
                                        @checked($compensation->isAdjustable())
                                    >
                                    Adjustable
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        id="is_adjustable"
                                        type="radio"
                                        name="is_adjustable"
                                        value="0"
                                        @checked(!$compensation->isAdjustable())
                                    >
                                    Not Adjustable
                                </label>
                                <x-common.validation-error property="is_adjustable" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="can_be_inputted_manually">
                                Can be inputted manually or not <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        id="can_be_inputted_manually"
                                        name="can_be_inputted_manually"
                                        type="radio"
                                        value="1"
                                        class="mt-3"
                                        @checked($compensation->canBeInputtedManually())
                                    >
                                    Inputted Manually
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        id="can_be_inputted_manually"
                                        type="radio"
                                        name="can_be_inputted_manually"
                                        value="0"
                                        @checked(!$compensation->canBeInputtedManually())
                                    >
                                    Not Inputted Manually
                                </label>
                                <x-common.validation-error property="can_be_inputted_manually" />
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

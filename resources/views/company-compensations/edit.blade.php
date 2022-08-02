@extends('layouts.app')

@section('title', 'Edit Company Compensation')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Company Compensation" />
        <form
            id="formOne"
            action="{{ route('company-compensations.update', $companyCompensation->id) }}"
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
                                    value="{{ $companyCompensation->name }}"
                                />
                                <x-common.icon
                                    name=""
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
                                            @selected($companyCompensation->isEarning())
                                        > Earning </option>
                                        <option
                                            value="deduction"
                                            @selected(!$companyCompensation->isEarning())
                                        > Deduction </option>
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
                                    @foreach ($compensations as $compensation)
                                        <option
                                            value="{{ $compensation->id }}"
                                            @selected($compensation->id == $companyCompensation->id)
                                        >{{ $compensation->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name=""
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
                                    value="{{ $companyCompensation->percentage }}"
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
                                    value="{{ $companyCompensation->default_value }}"
                                />
                                <x-common.icon
                                    name=""
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="default_value" />
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
                                        @checked($companyCompensation->isTaxable())
                                    >
                                    Taxable
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        id="is_taxable"
                                        type="radio"
                                        name="is_taxable"
                                        value="0"
                                        @checked(!$companyCompensation->isTaxable())
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
                                        @checked($companyCompensation->isAdjustable())
                                    >
                                    Adjustable
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        id="is_adjustable"
                                        type="radio"
                                        name="is_adjustable"
                                        value="0"
                                        @checked(!$companyCompensation->isAdjustable())
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
                                        @checked($companyCompensation->canBeInputtedManually())
                                    >
                                    Inputted Manually
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        id="can_be_inputted_manually"
                                        type="radio"
                                        name="can_be_inputted_manually"
                                        value="0"
                                        @checked(!$companyCompensation->canBeInputtedManually())
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

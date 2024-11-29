@extends('layouts.app')

@section('title', 'Edit Fee Master')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Fee Master - {{ $feeMaster->name }}
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('fee-masters.update', $feeMaster->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline is-mobile">
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.field>
                            <x-forms.label for="fee_type_id">
                                Fee Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="fee_type_id"
                                    name="fee_type_id"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Fee Type
                                    </option>
                                    @foreach ($feeTypes as $feeType)
                                        <option
                                            value="{{ $feeType->id }}"
                                            @selected($feeType->id == (old('fee_type_id', $feeMaster->feeType->id)))
                                        >
                                            {{ str()->ucfirst($feeType->name) }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="fee_type_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.field>
                            <x-forms.label for="due_date">
                                Due Date <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left is-fullwidth">
                                <x-forms.input
                                    class="is-fullwidth"
                                    id="due_date"
                                    name="due_date"
                                    type="date"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ $feeMaster->due_date->toDateString() ?? now()->toDateString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="due_date" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.field>
                            <x-forms.label for="amount">
                                Amount <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    placeholder="Amount"
                                    value="{{ $feeMaster->amount ?? old('amount') }}"
                                />
                                <x-common.icon
                                    name="fas fa-dollar"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="amount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.label>
                            Fine Type <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="fine_type"
                                    name="fine_type"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Fine Type
                                    </option>
                                    <option
                                        value="percentage"
                                        @selected($feeMaster->fine_type == 'percentage')
                                    > Percentage </option>
                                    <option
                                        value="amount"
                                        @selected($feeMaster->fine_type == 'amount')
                                    > Amount </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="fine_type" />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    id="fine_amount"
                                    name="fine_amount"
                                    type="number"
                                    placeholder="Fine Amount"
                                    value="{{ $feeMaster->fine_amount ?? old('fine_amount') }}"
                                    autocomplete="fine_amount"
                                />
                                <x-common.icon
                                    name="fas fa-dollar-sign"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="fine_amount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

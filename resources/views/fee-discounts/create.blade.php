
@extends('layouts.app')

@section('title', 'Create Discount')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        New Discount
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('fee-discounts.store') }}"
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
                                Name<sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="name"
                                    id="name"
                                    placeholder="Fee Discount Name"
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
                        <x-forms.field>
                            <x-forms.label for="discount_code">
                                Discount Code<sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="discount_code"
                                    id="discount_code"
                                    placeholder="Fee Discount Code"
                                    value="{{ old('discount_code') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-code"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="discount_code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="discount_type">
                               Discount Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="discount_type"
                                    name="discount_type"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Type
                                    </option>
                                    <option
                                        value="once"
                                        @selected(old('once') == 'once')
                                    >
                                        Once
                                    </option>
                                    <option
                                        value="year"
                                        @selected(old('year') == 'year')
                                    >
                                        Year
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="discount_type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="amount">
                                Discount Amount<sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    placeholder="Discount Amount"
                                    value="{{ old('amount') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-dollar"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="amount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    class="summernote textarea"
                                    name="description"
                                    id="description"
                                    placeholder="Description"
                                >
                                    {{ old('name') ?? '' }}
                            </x-forms.textarea>
                                <x-common.validation-error property="name" />
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

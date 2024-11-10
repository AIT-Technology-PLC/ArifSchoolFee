@extends('layouts.app')

@section('title', 'Edit Fee Discount')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Discount - {{ $feeDiscount->name }}
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form id="formOne" 
            action="{{ route('fee-discounts.update', $feeDiscount->id) }}" 
            method="post"
            enctype="multipart/form-data" 
            novalidate
        >
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
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
                                    placeholder="Discount Name"
                                    value="{{ $feeDiscount->name ?? '' }}" 
                                />
                                <x-common.icon 
                                    name="fas fa-heading" 
                                    class="is-large is-left" />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="discount_code">
                                Discount Code <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input 
                                    type="text" 
                                    name="discount_code" 
                                    id="discount_code"
                                    placeholder="Fee Discount Code" 
                                    value="{{ $feeDiscount->discount_code ?? '' }}" 
                                />
                                <x-common.icon 
                                    name="fas fa-code" 
                                    class="is-large is-left" />
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
                                        @selected(old('discount_type' , $feeDiscount->discount_type) == 'once')
                                    >
                                        Once
                                    </option>
                                    <option 
                                        value="year" 
                                        @selected(old('discount_type', $feeDiscount->discount_type) == 'year')
                                    >
                                        Year
                                    </option>
                                </x-forms.select>
                                <x-common.icon 
                                    name="fas fa-sort" 
                                    class="is-small is-left" />
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
                                    value="{{ $feeDiscount->amount ?? '' }}" 
                                />
                                <x-common.icon 
                                    name="fas fa-dollar" 
                                    class="is-large is-left" />
                                <x-common.validation-error property="amount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea 
                                    class="summernote textarea"
                                    name="description" 
                                    id="description" 
                                    placeholder="Description or Note to be taken"
                                >
                                    {{ $feeDiscount->description }} 
                                </x-forms.textarea>
                                <x-common.validation-error property="description" />
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

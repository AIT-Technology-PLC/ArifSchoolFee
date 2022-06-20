@extends('layouts.app')

@section('title', 'Edit JobExtra')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit JobExtra" />
        <form
            id="formOne"
            action="{{ route('job-extras.update', $jobExtra->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="product_id">
                                Product <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-common.product-list
                                    id="product_id"
                                    name="product_id"
                                    key=""
                                    selected-product-id="{{ $jobExtra->product_id }}"
                                    x-init="initializeSelect2($el)"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="product_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="quantity">
                                Quantity <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="input"
                                    name="quantity"
                                    id="quantity"
                                    value="{{ $jobExtra->quantity }}"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="quantity" />
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
                                            value="Input"
                                            {{ $jobExtra->type == 'Input' ? 'selected' : '' }}
                                        > Input </option>
                                        <option
                                            value="Remaining"
                                            {{ $jobExtra->type == 'Remaining' ? 'selected' : '' }}
                                        > Remaining </option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="type" />
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

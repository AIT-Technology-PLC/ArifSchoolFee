@extends('layouts.app')

@section('title', 'Create Bill Of Material')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Bill Of Material" />
        <form
            id="formOne"
            action="{{ route('bill-of-materials.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.label for="">
                            Product <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control
                                class="has-icons-left"
                                style="width: 30%"
                            >
                                <x-common.category-list />
                            </x-forms.control>
                            <x-forms.control
                                class="has-icons-left is-expanded"
                                style="width: 70%"
                            >
                                <x-common.new-product-list
                                    id="product_id"
                                    name="product_id"
                                    :type="['Finished Goods']"
                                    x-bind:value="`{{ old('product_id') }}`"
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
                            <x-forms.label for="name">
                                Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="customer_id">
                                Customer <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="select is-fullwidth has-icons-left">
                                <x-common.customer-list
                                    id="customer_id"
                                    name="customer_id"
                                    key=""
                                    :selected-id="old('customer_id') ?? ''"
                                />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="customer_id" />
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
                                        type="radio"
                                        name="is_active"
                                        value="1"
                                        class="mt-3"
                                        @checked(old('is_active'))
                                    >
                                    Active
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        type="radio"
                                        name="is_active"
                                        value="0"
                                        @checked(!old('is_active'))
                                    >
                                    Not Active
                                </label>
                                <x-common.validation-error property="is_active" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
            @include('bill-of-materials.details-form', ['data' => session()->getOldInput()])
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>

    @can('Create Customer')
        <x-common.customer-form-modal />
    @endcan
@endsection

@extends('layouts.app')

@section('title', 'Edit Bill Of Material')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Bill Of Material" />
        <form
            id="formOne"
            action="{{ route('bill-of-materials.update', $billOfMaterial->id) }}"
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
                                Product <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-common.product-list
                                    id="product_id"
                                    name="product_id"
                                    key=""
                                    selected-product-id="{{ $billOfMaterial->product_id }}"
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
                            <x-forms.label for="name">
                                Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="name"
                                    value="{{ $billOfMaterial->name }}"
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
                                        @checked($billOfMaterial->isActive())
                                    >
                                    Active
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        type="radio"
                                        name="is_active"
                                        value="0"
                                        @checked(!$billOfMaterial->isActive())
                                    >
                                    Not Active
                                </label>
                                <x-common.validation-error property="is_active" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
            @include('bill-of-materials.details-form', ['data' => ['billOfMaterial' => $billOfMaterial->billOfMaterialDetails]])
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

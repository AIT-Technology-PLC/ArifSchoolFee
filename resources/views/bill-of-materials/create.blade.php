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
                        <div class="field">
                            <label
                                for="product_id"
                                class="label text-green has-text-weight-normal"
                            >ID <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    class="input"
                                    type="number"
                                    name="product_id"
                                >
                                <span class="icon is-large is-left">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                @error('product_id')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="name"
                                class="label text-green has-text-weight-normal"
                            >Name <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    class="input"
                                    type="text"
                                    name="name"
                                >
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-large is-left"
                                />
                                @error('name')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label
                                    for="is_active"
                                    class="label text-green has-text-weight-normal"
                                > Active or not <sup class="has-text-danger">*</sup> </label>
                                <div class="control">
                                    <label class="radio has-text-grey has-text-weight-normal">
                                        <input
                                            type="radio"
                                            name="is_active"
                                            value="1"
                                            class="mt-3"
                                            {{ old('is_active') ? '' : 'checked' }}
                                        >
                                        Active
                                    </label>
                                    <label class="radio has-text-grey has-text-weight-normal mt-2">
                                        <input
                                            type="radio"
                                            name="is_active"
                                            value="0"
                                            {{ old('is_active') ? 'checked' : '' }}
                                        >
                                        Not Active
                                    </label>
                                    @error('is_active')
                                        <span
                                            class="help has-text-danger"
                                            role="alert"
                                        >
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-content.main
                    x-data="billOfMaterialMasterDetailForm({{ json_encode(session()->getOldInput()) }})"
                    x-init="setErrors({{ json_encode($errors->get('billOfMaterial.*')) }})"
                >
                    <x-common.fail-message :message="session('failedMessage')" />
                    <template
                        x-for="(billOfMaterial, index) in billOfMaterials"
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
                                    <div class="column is-6">
                                        <x-forms.field>
                                            <x-forms.label x-bind:for="`billOfMaterial[${index}][product_id]`">
                                                Product <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-common.product-list
                                                    x-bind:id="`billOfMaterial[${index}][product_id]`"
                                                    x-bind:name="`billOfMaterial[${index}][product_id]`"
                                                    x-model="billOfMaterial.product_id"
                                                />
                                                <x-common.icon
                                                    name="fas fa-th"
                                                    class="is-large is-left"
                                                />
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="getErrors(`billOfMaterial.${index}.product_id`)"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6">
                                        <x-forms.field>
                                            <x-forms.label x-bind:for="`billOfMaterial[${index}][quantity]`">
                                                Quantity <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    x-bind:id="`billOfMaterial[${index}][quantity]`"
                                                    x-bind:name="`billOfMaterial[${index}][quantity]`"
                                                    x-model="billOfMaterial.quantity"
                                                />
                                                <x-common.icon
                                                    name="fas fa-balance-scale"
                                                    class="is-large is-left"
                                                />
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="getErrors(`billOfMaterial.${index}.quantity`)"
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
                        label="Add More Item"
                        class="bg-purple has-text-white is-small ml-3 mt-6"
                        x-on:click="add"
                    />
                </x-content.main>
                <x-content.footer>
                    <x-common.save-button />
                </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

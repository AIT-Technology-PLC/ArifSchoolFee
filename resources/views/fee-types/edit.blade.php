@extends('layouts.app')

@section('title', 'Edit Fee Type')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Category - {{ $feeType->name }}
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('fee-types.update', $feeType->id) }}"
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
                                    placeholder="Fee Type Name"
                                    value="{{ $feeType->name ?? '' }}"
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
                            <x-forms.label for="fee_group_id">
                                Fee Group <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                            <x-forms.select 
                                class="is-fullwidth"
                                id="fee_group_id"
                                name="fee_group_id"
                            >
                                @foreach ($feeGroups as $feeGroup)
                                    <option
                                        value="{{ $feeGroup->id }}"
                                        @selected(old('fee_group_id', $feeType->fee_group_id) == $feeGroup->id)
                                    >
                                        {{ $feeGroup->name }}
                                    </option>
                                @endforeach
                                </x-forms.select>
                                <x-common.icon 
                                    name="fas fa-sort" 
                                    class="is-small is-left" />
                                <x-common.validation-error property="fee_group_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-full">
                        <x-forms.field>
                            <x-forms.label for="description">
                               Description <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    class="summernote textarea"
                                    name="description"
                                    id="description"
                                    placeholder="Description"
                                >
                                    {{ $feeType->description}}
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

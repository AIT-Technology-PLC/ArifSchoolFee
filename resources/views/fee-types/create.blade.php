@extends('layouts.app')

@section('title', 'Create New Type')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        New Type
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form 
            id="formOne" 
            action="{{ route('fee-types.store') }}" 
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
                                    placeholder="Fee Type Name"
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
                            <x-forms.label for="fee_group_id">
                                Fee Group <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select 
                                    class="is-fullwidth" 
                                    id="fee_group_id" 
                                    name="fee_group_id">
                                    <option 
                                        disabled 
                                        selected
                                    >Select Fee Group</option>
                                        @foreach ($feeGroups as $feeGroup)
                                            <option
                                                value="{{ $feeGroup->id }}"
                                                {{ old('fee_group_id') == $feeGroup->id ? 'selected' : '' }}
                                            >{{ $feeGroup->name }}</option>
                                        @endforeach
                                </x-forms.select>
                                <x-common.icon 
                                    name="fas fa-sort" 
                                    class="is-small is-left" />
                                <x-common.validation-error property="fee_group_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="name">
                                Description
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.textarea 
                                    class="summernote textarea" 
                                    name="description" 
                                    id="description"
                                    placeholder="Description" 
                                >
                                    {{ old('description') ?? '' }} 
                                </x-forms.textarea>
                                <x-common.validation-error property="description" />
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

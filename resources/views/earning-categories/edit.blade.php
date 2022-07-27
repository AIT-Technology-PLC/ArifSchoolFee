@extends('layouts.app')

@section('title', 'Edit Earning Category')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Earning Category" />
        <form
            id="formOne"
            action="{{ route('earning-categories.update', $earningCategory->id) }}"
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
                                    placeholder="Category Name"
                                    value="{{ $earningCategory->name }}"
                                />
                                <x-common.icon
                                    name="fas fa-user"
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
                                            value="Before Tax"
                                            @selected($earningCategory->isBeforeTax())
                                        > Before Tax </option>
                                        <option
                                            value="After Tax"
                                            @selected(!$earningCategory->isBeforeTax())
                                        > After Tax </option>
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
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

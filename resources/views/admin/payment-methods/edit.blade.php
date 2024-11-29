@extends('layouts.app')

@section('title', 'Edit Payment Method')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Payment Method
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.payment-methods.update', $paymentMethod->id) }}"
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
                                    placeholder="Name"
                                    value="{{ old('name') ?? $paymentMethod->name }}"
                                />
                                <x-common.icon
                                    name="fas fa-credit-card"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="enabled">
                                Status <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="enabled"
                                    name="enabled"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Status
                                    </option>
                                    <option
                                        value="1"
                                        @selected(old('enabled', $paymentMethod->enabled) == '1')
                                    >
                                        Enable
                                    </option>
                                    <option
                                        value="0"
                                        @selected(old('enabled', $paymentMethod->enabled) == '0')
                                    >
                                        Disable
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="enabled" />
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

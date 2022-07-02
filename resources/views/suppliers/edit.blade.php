@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Supplier - {{ $supplier->company_name }}" />
        <form
            id="formOne"
            action="{{ route('suppliers.update', $supplier->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="company_name">
                                Company Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    id="company_name"
                                    name="company_name"
                                    placeholder="Company Name"
                                    value="{{ $supplier->company_name ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-building"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="company_name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="tin">
                                TIN <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    id="tin"
                                    name="tin"
                                    placeholder="Tin No"
                                    value="{{ $supplier->tin ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="tin" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="address">
                                Address <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    id="address"
                                    name="address"
                                    placeholder="Address"
                                    value="{{ $supplier->address ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-map-marker-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="address" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="contact_name">
                                Contact Name <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    id="contact_name"
                                    name="contact_name"
                                    placeholder="Contact name"
                                    value="{{ $supplier->contact_name ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-address-book"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="contact_name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="email">
                                Email <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    id="email"
                                    name="email"
                                    placeholder="Email Address"
                                    value="{{ $supplier->email ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-at"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="email" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="phone">
                                Phone <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    placeholder="Phone/Telephone"
                                    value="{{ $supplier->phone ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-phone"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="phone" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="country">
                                Country <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="country"
                                    name="country"
                                >
                                    <option
                                        selected
                                        disabled
                                    > Select Country/City </option>
                                    @if ($supplier->country)
                                        <option
                                            value="{{ $supplier->country }}"
                                            selected
                                        > {{ $supplier->country }} </option>
                                    @endif
                                    <optgroup label="Ethiopian Cities">
                                        @include('lists.cities')
                                    </optgroup>
                                    <optgroup label="Others">
                                        @include('lists.countries')
                                    </optgroup>
                                    <option value="">None</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-globe"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="country" />
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

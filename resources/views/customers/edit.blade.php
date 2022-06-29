@extends('layouts.app')

@section('title', 'Edit Customer - {{ $customer->company_name }}')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title=" Edit Customer - {{ $customer->company_name }}" />
        <form
            id="formOne"
            action="{{ route('customers.update', $customer->id) }}"
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
                                    id="company_name"
                                    name="company_name"
                                    type="text"
                                    placeholder="Company Name"
                                    value="{{ $customer->company_name ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-building"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="company_name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="credit_amount_limit">
                                Credit Limit <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="credit_amount_limit"
                                    name="credit_amount_limit"
                                    type="number"
                                    placeholder="Credit Limit"
                                    value="{{ $customer->credit_amount_limit }}"
                                />
                                <x-common.icon
                                    name="fas fa-dollar-sign"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="credit_amount_limit" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="tin">
                                TIN <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="tin"
                                    name="tin"
                                    type="number"
                                    placeholder="Tin No"
                                    value="{{ $customer->tin ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="tin" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="address">
                                Address <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="address"
                                    name="address"
                                    type="text"
                                    placeholder="Address"
                                    value="{{ $customer->address ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-map-marker-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="address" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="contact_name">
                                Contact Name <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="contact_name"
                                    name="contact_name"
                                    type="text"
                                    placeholder="Contact Name"
                                    value="{{ $customer->contact_name ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-address-book"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="contact_name" />
                                </x-forms.cont>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="email">
                                Email <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="email"
                                    name="email"
                                    type="text"
                                    placeholder="Email Address"
                                    value="{{ $customer->email ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-at"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="email" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="phone">
                                Phone <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    placeholder="Phone/Telephone"
                                    value="{{ $customer->phone ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-phone"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="phone" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="country">
                                Country <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="country"
                                    name="country"
                                >
                                    <option
                                        selected
                                        disabled
                                    > Select Country/City </option>
                                    @if ($customer->country)
                                        <option
                                            value="{{ $customer->country }}"
                                            selected
                                        > {{ $customer->country }} </option>
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

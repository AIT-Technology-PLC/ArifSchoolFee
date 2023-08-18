@extends('layouts.app')

@section('title', 'Create New Warehouse')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Warehouse" />
        <form
            id="formOne"
            action="{{ route('warehouses.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <x-common.fail-message :message="session('limitReachedMessage')" />
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="name">
                                Warehouse Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="name"
                                    id="name"
                                    placeholder="Warehouse Name"
                                    value="{{ old('name') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-warehouse"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="location">
                                Location <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="location"
                                    id="location"
                                    placeholder="Location: Building, Street"
                                    value="{{ old('location') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-location-arrow"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="location" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (userCompany()->hasIntegration('Point of Sale'))
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="pos_provider">
                                    Point of Sale Provider <sup class="has-text-danger"> </sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="pos_provider"
                                        name="pos_provider"
                                    >
                                        <option
                                            selected
                                            disabled
                                        > Select Provider</option>
                                        <option
                                            value="Peds"
                                            @selected(old('pos_provider') == 'Peds')
                                        >Peds</option>
                                        <option
                                            value=""
                                            @selected(old('pos_provider') == '')
                                        >None</option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-cash-register"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="pos_provider" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="host_address">
                                    Host Address <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        type="text"
                                        name="host_address"
                                        id="host_address"
                                        placeholder="Host Address"
                                        value="{{ old('host_address') ?? '' }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-globe"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="host_address" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
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
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_sales_store">
                                Store Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        type="radio"
                                        name="is_sales_store"
                                        value="1"
                                        class="mt-3"
                                        @checked(old('is_sales_store'))
                                    >
                                    Sales Store
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        type="radio"
                                        name="is_sales_store"
                                        value="0"
                                        @checked(!old('is_sales_store'))
                                    >
                                    Main Store
                                </label>
                                <x-common.validation-error property="is_sales_store" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="can_be_sold_from">
                                Can be sold from? <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        type="radio"
                                        name="can_be_sold_from"
                                        value="1"
                                        class="mt-3"
                                        @checked(old('can_be_sold_from'))
                                    >
                                    Yes
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        type="radio"
                                        name="can_be_sold_from"
                                        value="0"
                                        @checked(!old('can_be_sold_from'))
                                    >
                                    No
                                </label>
                                <x-common.validation-error property="can_be_sold_from" />
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
                                    value="{{ old('email') ?? '' }}"
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
                                    value="{{ old('phone') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-phone"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="phone" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="customer_id">
                                Description <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="pl-6"
                                    placeholder="Description or note to be taken"
                                >
                                    {{ old('description') ?? '' }}
                                </x-forms.textarea>
                                <x-common.icon
                                    name="fas fa-edit"
                                    class="is-large is-left"
                                />
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

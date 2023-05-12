@extends('layouts.app')

@section('title', 'Create Tender Opportunity')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Tender Opportunity" />
        <form
            id="formOne"
            action="{{ route('tender-opportunities.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Reference No. <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="code"
                                    id="code"
                                    value="{{ old('code') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="published_on">
                                Published On <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="datetime-local"
                                    name="published_on"
                                    id="published_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('published_on') ?? now()->toDateTimeLocalString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="published_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="tender_status_id">
                                Status <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="tender_status_id"
                                    name="tender_status_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >
                                        Select Status
                                    </option>
                                    @foreach ($tenderStatuses as $tenderStatus)
                                        <option
                                            value="{{ $tenderStatus->id }}"
                                            {{ old('tender_status_id') == $tenderStatus->id ? 'selected' : '' }}
                                        >
                                            {{ $tenderStatus->status }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-info"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="tender_status_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="customer_id">
                                Customer <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <div class="select is-fullwidth">
                                    <x-common.customer-list :selected-id="old('customer_id') ?? ''" />
                                </div>
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="customer_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="source">
                                Source <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="source"
                                    id="source"
                                    value="{{ old('source') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-newspaper"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="source" />
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
                                    type="text"
                                    name="address"
                                    id="address"
                                    value="{{ old('address') ?? '' }}"
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
                        <x-forms.label for="currency">
                            Price <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    id="currency"
                                    name="currency"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Currency
                                    </option>
                                    <option
                                        value="AED"
                                        {{ old('currency') == 'AED' ? 'selected' : '' }}
                                    >
                                        AED - UAE Dirham
                                    </option>
                                    <option
                                        value="CHF"
                                        {{ old('currency') == 'CHF' ? 'selected' : '' }}
                                    >
                                        CHF - Swiss Frank
                                    </option>
                                    <option
                                        value="CNY"
                                        {{ old('currency') == 'CNY' ? 'selected' : '' }}
                                    >
                                        CNY - China Yuan
                                    </option>
                                    <option
                                        value="ETB"
                                        {{ old('currency') == 'ETB' ? 'selected' : '' }}
                                    >
                                        ETB - Ethiopian Birr
                                    </option>
                                    <option
                                        value="EUR"
                                        {{ old('currency') == 'EUR' ? 'selected' : '' }}
                                    >
                                        EUR - Euro Union Countries
                                    </option>
                                    <option
                                        value="GBP"
                                        {{ old('currency') == 'GBP' ? 'selected' : '' }}
                                    >
                                        GBP - GB Pound Sterling
                                    </option>
                                    <option
                                        value="SAR"
                                        {{ old('currency') == 'SAR' ? 'selected' : '' }}
                                    >
                                        SAR - Saudi Riyal
                                    </option>
                                    <option
                                        value="USD"
                                        {{ old('currency') == 'USD' ? 'selected' : '' }}
                                    >
                                        USD - US Dollar
                                    </option>
                                    <option value="">
                                        None
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-dollar-sign"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    id="price"
                                    name="price"
                                    type="number"
                                    placeholder="Price"
                                    value="{{ old('price') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-money-check"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                        <x-common.validation-error property="currency" />
                        <x-common.validation-error property="price" />
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="body">
                                Body <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.textarea
                                    name="body"
                                    id="body"
                                    class="summernote"
                                    placeholder="Comments, remarks, or sidenotes"
                                >
                                    {{ old('body') ?? '' }}
                                </x-forms.textarea>
                                <x-common.validation-error property="body" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="comments">
                                Comments <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.textarea
                                    name="comments"
                                    id="comments"
                                    class="summernote"
                                    placeholder="Comments, remarks, or sidenotes"
                                >
                                    {{ old('comments') ?? '' }}
                                </x-forms.textarea>
                                <x-common.validation-error property="comments" />
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

    @can('Create Customer')
        <div x-bind:class="Alpine.store('openCreateCustomerModal') ? '' : 'is-hidden'">
            <livewire:create-customer />
        </div>
    @endcan
@endsection

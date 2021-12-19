@extends('layouts.app')

@section('title', 'Edit Tender Opportunity')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Tender Opportunity" />
        <form
            id="formOne"
            action="{{ route('tender-opportunities.update', $tenderOpportunity->id) }}"
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
                            <x-forms.label for="code">
                                Reference No. <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="code"
                                    id="code"
                                    value="{{ $tenderOpportunity->code }}"
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
                                    value="{{ $tenderOpportunity->published_on->toDateTimeLocalString() }}"
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
                                            {{ $tenderOpportunity->tender_status_id == $tenderStatus->id ? 'selected' : '' }}
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
                                    <x-common.customer-list :selected-customer-id="$tenderOpportunity->customer_id ?? ''" />
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
                                    value="{{ $tenderOpportunity->source }}"
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
                                    value="{{ $tenderOpportunity->address ?? '' }}"
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
                                        {{ ($tenderOpportunity->currency ?? '') == 'AED' ? 'selected' : '' }}
                                    >
                                        AED - UAE Dirham
                                    </option>
                                    <option
                                        value="CHF"
                                        {{ ($tenderOpportunity->currency ?? '') == 'CHF' ? 'selected' : '' }}
                                    >
                                        CHF - Swiss Frank
                                    </option>
                                    <option
                                        value="CNY"
                                        {{ ($tenderOpportunity->currency ?? '') == 'CNY' ? 'selected' : '' }}
                                    >
                                        CNY - China Yuan
                                    </option>
                                    <option
                                        value="ETB"
                                        {{ ($tenderOpportunity->currency ?? '') == 'ETB' ? 'selected' : '' }}
                                    >
                                        ETB - Ethiopian Birr
                                    </option>
                                    <option
                                        value="EUR"
                                        {{ ($tenderOpportunity->currency ?? '') == 'EUR' ? 'selected' : '' }}
                                    >
                                        EUR - Euro Union Countries
                                    </option>
                                    <option
                                        value="GBP"
                                        {{ ($tenderOpportunity->currency ?? '') == 'GBP' ? 'selected' : '' }}
                                    >
                                        GBP - GB Pound Sterling
                                    </option>
                                    <option
                                        value="SAR"
                                        {{ ($tenderOpportunity->currency ?? '') == 'SAR' ? 'selected' : '' }}
                                    >
                                        SAR - Saudi Riyal
                                    </option>
                                    <option
                                        value="USD"
                                        {{ ($tenderOpportunity->currency ?? '') == 'USD' ? 'selected' : '' }}
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
                                    value="{{ $tenderOpportunity->price ?? '' }}"
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
                                    {{ $tenderOpportunity->body }}
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
                                    {{ $tenderOpportunity->comments ?? '' }}
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
@endsection

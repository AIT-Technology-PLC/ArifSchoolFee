@extends('layouts.app')

@section('title', 'Create New Return')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Return" />
        <form
            id="formOne"
            action="{{ route('returns.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main x-data="returnMaster('{{ old('gdn_id') }}', '{{ old('customer_id') }}')">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Return Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    :readonly="!userCompany()->isEditingReferenceNumberEnabled()"
                                    value="{{ $currentReturnCode }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        x-show="isShowGdnSelect"
                        class="column is-6"
                    >
                        <x-forms.field>
                            <x-forms.label for="gdn_id">
                                Delivery Order No <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="gdn_id"
                                    name="gdn_id"
                                    x-init="select2Gdn"
                                >
                                    <option></option>
                                    @foreach ($gdns as $groupedGdn)
                                        <optgroup label="{{ $groupedGdn->first()->warehouse->name }}"></optgroup>
                                        @foreach ($groupedGdn as $gdn)
                                            <option
                                                value="{{ $gdn->id }}"
                                                @selected(old('gdn_id') == $gdn->id)
                                            >
                                                #{{ $gdn->code }} ({{ $gdn->issued_on->toFormattedDateString() }})
                                            </option>
                                        @endforeach
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-file-invoice"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="gdn_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        x-cloak
                        class="column is-6"
                        x-show="isShowGdnSelect && gdn?.customer?.company_name"
                    >
                        <x-forms.field>
                            <x-forms.label for="code">
                                Customer <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    readonly
                                    x-bind:value="gdn?.customer?.company_name"
                                />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        x-show="isShowCustomerSelect"
                        class="column is-6"
                    >
                        <x-forms.field>
                            <x-forms.label for="customer_id">
                                Customer <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left select is-fullwidth">
                                <x-common.customer-list
                                    :selected-id="old('customer_id')"
                                    x-init="select2Customer"
                                />
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
                            <x-forms.label for="code">
                                Issued On <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="datetime-local"
                                    name="issued_on"
                                    id="issued_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('issued_on') ?? now()->toDateTimeLocalString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="issued_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
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

            @include('returns.details-form', ['data' => session()->getOldInput()])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

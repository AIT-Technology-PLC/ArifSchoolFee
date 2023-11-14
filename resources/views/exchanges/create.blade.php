@extends('layouts.app')

@section('title', 'Create New Exchange')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Exchange" />
        <form
            id="formOne"
            action="{{ route('exchanges.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main x-data="exchangeMaster('{{ old('gdn_id') }}', '{{ old('sale_id') }}')">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Exchange Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    :readonly="!userCompany()->isEditingReferenceNumberEnabled()"
                                    value="{{ $currentExchangeCode }}"
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
                            <x-forms.label for="gdn_id">
                                Delivery Order No
                                <sup class="has-text-danger">
                                    {{ userCompany()->isReturnLimitedBySales() ? '*' : '' }}
                                </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="gdn_id"
                                    name="gdn_id"
                                    x-init="select2Gdn"
                                    x-model="gdnId"
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
                    @if (userCompany()->canSaleSubtract())
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="sale_id">
                                    Invoice No
                                    <sup class="has-text-danger">
                                        {{ userCompany()->isReturnLimitedBySales() ? '*' : '' }}
                                    </sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="sale_id"
                                        name="sale_id"
                                        x-init="select2Sale"
                                        x-model="saleId"
                                    >
                                        <option></option>
                                        @foreach ($sales as $groupedSale)
                                            <optgroup label="{{ $groupedSale->first()->warehouse->name }}"></optgroup>
                                            @foreach ($groupedSale as $sale)
                                                <option
                                                    value="{{ $sale->id }}"
                                                    @selected(old('sale_id') == $sale->id)
                                                >
                                                    #{{ $sale->code }} ({{ $sale->issued_on->toFormattedDateString() }})
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-file-invoice"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="sale_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <x-common.custom-field-form
                        model-type="exchange"
                        :input="old('customField')"
                    />
                </div>
            </x-content.main>

            @include('exchanges.partials.details-form', ['data' => session()->getOldInput()])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

@extends('layouts.app')

@section('title', 'Edit Price')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Price" />
        <form
            id="formOne"
            action="{{ route('prices.update', $price->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main x-data="priceMasterDetailForm({{ json_encode(compact('price')) }})">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label>
                                Product <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    value="{{ $price->product->name }}"
                                    disabled
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="type">
                                Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="type"
                                    name="type"
                                    x-model="prices.type"
                                    x-on:change="changePriceType(prices)"
                                >
                                    <option disabled>
                                        Select Type
                                    </option>
                                    <option
                                        value="fixed"
                                        {{ $price->isFixed() ? 'selected' : '' }}
                                    >
                                        Fixed
                                    </option>
                                    <option
                                        value="range"
                                        {{ $price->isFixed() ? '' : 'selected' }}
                                    >
                                        Range
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-tags"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6 {{ $price->isFixed() ? '' : 'is-hidden' }}"
                        x-bind:class="{ 'is-hidden': !isPriceFixed(prices.type) }"
                    >
                        <x-forms.label for="fixed_price">
                            Fixed Price <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    id="fixed_price"
                                    name="fixed_price"
                                    type="number"
                                    placeholder="Fixed price"
                                    value="{{ $price->fixed_price }}"
                                    x-model="prices.fixed_price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="fixed_price" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6 {{ $price->isFixed() ? 'is-hidden' : '' }}"
                        x-bind:class="{ 'is-hidden': isPriceFixed(prices.type) }"
                    >
                        <x-forms.label for="min_price">
                            Min. Price <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    id="min_price"
                                    name="min_price"
                                    type="number"
                                    placeholder="Min. price"
                                    value="{{ $price->min_price }}"
                                    x-model="prices.min_price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="min_price" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6 {{ $price->isFixed() ? 'is-hidden' : '' }}"
                        x-bind:class="{ 'is-hidden': isPriceFixed(prices.type) }"
                    >
                        <x-forms.label for="max_price">
                            Max. Price <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    id="max_price"
                                    name="max_price"
                                    type="number"
                                    placeholder="Max. price"
                                    value="{{ $price->max_price }}"
                                    x-model="prices.max_price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="max_price" />
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

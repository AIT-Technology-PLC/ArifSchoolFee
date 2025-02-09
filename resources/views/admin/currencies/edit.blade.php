@extends('layouts.app')

@section('title', 'Edit Currency')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Currency
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.currencies.update', $currency->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-4">
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
                                    value="{{ old('name') ?? $currency->name }}"
                                />
                                <x-common.icon
                                    name="fas fa-dollar"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Code <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="code"
                                    name="code"
                                    type="text"
                                    placeholder="Code"
                                    value="{{ old('code') ?? $currency->code }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="symbol">
                                Symbol <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="symbol"
                                    name="symbol"
                                    type="text"
                                    placeholder="Symbol"
                                    value="{{ old('symbol') ?? $currency->symbol }}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="symbol" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="exchange_rate">
                                Exchange Rate <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="exchange_rate"
                                    id="exchange_rate"
                                    placeholder="Exchange Rate"
                                    value="{{ old('exchange_rate') ?? $currency->exchange_rate }}"
                                />
                                <x-common.icon
                                    name="fas fa-exchange-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="exchange_rate" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="enabled">
                                Enable Exchange Rate <sup class="has-text-danger">*</sup>
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
                                        @selected(old('enabled', $currency->enabled) == '1')
                                    >
                                        Yes
                                    </option>
                                    <option
                                        value="0"
                                        @selected(old('enabled', $currency->enabled) == '0')
                                    >
                                        No
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

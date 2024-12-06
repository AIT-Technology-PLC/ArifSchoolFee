@extends('layouts.app')

@section('title', 'Edit Payment Gateway')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Payment Gateway
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.payment-gateways.update', $paymentGateway->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12-mobile is-4-tablet is-4-desktop">
                        <x-forms.field>
                            <x-forms.label for="company_id">
                                School <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="company_id"
                                    name="company_id"
                                >
                                <option 
                                    selected
                                    disabled
                                >Select School</option>
                                    @foreach ($schools as $school)
                                        <option
                                            value="{{ $school->id }}"
                                            @selected(old('company_id', $paymentGateway->company_id) == $school->id)
                                        >{{ $school->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-school"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="company_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12-mobile is-4-tablet is-4-desktop">
                        <x-forms.field>
                            <x-forms.label for="payment_method_id">
                                Payment Method <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="payment_method_id"
                                    name="payment_method_id"
                                >
                                <option 
                                    selected
                                    disabled
                                >Select Payment Method</option>
                                    @foreach ($methods as $method)
                                        <option
                                            value="{{ $method->id }}"
                                            @selected(old('payment_method_id', $paymentGateway->payment_method_id) == $method->id)
                                        >{{ $method->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-credit-card"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="payment_method_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12-mobile is-4-tablet is-4-desktop">
                        <x-forms.field>
                            <x-forms.label for="merchant_id">
                                Merchant Id <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="merchant_id"
                                    id="merchant_id"
                                    placeholder="Merchant Id"
                                    value="{{ old('merchant_id') ?? '' }}"
                                    value="{{ old('merchant_id') ?? $paymentGateway->merchant_id }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="merchant_id" />
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

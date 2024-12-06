@extends('layouts.app')

@section('title', 'PayPal Setting')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        PayPal Setting
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.paypal-settings.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <x-common.success-message :message="session('successMessage')" />
                <div class="columns is-marginless is-multiline is-centered">
                    <div class="column is-8">
                        <x-forms.field>
                            <x-forms.label for="PAYPAL_CLIENT_ID">
                                PayPal Client ID <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="PAYPAL_CLIENT_ID"
                                    name="PAYPAL_CLIENT_ID"
                                    type="text"
                                    placeholder="PAYPAL_CLIENT_ID"
                                    value="{{ get_static_option('PAYPAL_CLIENT_ID') }}"
                                />
                                <x-common.icon
                                    name="fas fa-id-badge"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="PAYPAL_CLIENT_ID" />
                            </x-forms.control>
                        </x-forms.field>

                        <x-forms.field>
                            <x-forms.label for="PAYPAL_SECRET_KEY">
                                PayPal Secret Key <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="PAYPAL_SECRET_KEY"
                                    name="PAYPAL_SECRET_KEY"
                                    type="text"
                                    placeholder="PAYPAL_SECRET_KEY"
                                    value="{{ get_static_option('PAYPAL_SECRET_KEY') }}"
                                />
                                <x-common.icon
                                    name="fas fa-key"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="PAYPAL_SECRET_KEY" />
                            </x-forms.control>
                        </x-forms.field>

                        <x-forms.field>
                            <x-forms.label for="PAYPAL_MODE">
                                PayPal Mode <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="PAYPAL_MODE"
                                    name="PAYPAL_MODE"
                                    type="text"
                                    placeholder="PAYPAL_MODE"
                                    value="{{ get_static_option('PAYPAL_MODE') }}"
                                />
                                <x-common.icon
                                    name="fas fa-spinner"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="PAYPAL_MODE" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-content.main>
            <x-content.footer>
                <div class="columns is-marginless">
                    <div class="column is-paddingless">
                        <div class="buttons is-centered">
                            <button class="button is-white text-blue" type="reset">
                                <span class="icon">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span>
                                    Cancel
                                </span>
                            </button>
                            <button id="saveButton" class="button bg-softblue has-text-white">
                                <span class="icon">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span>
                                    Change Setting
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

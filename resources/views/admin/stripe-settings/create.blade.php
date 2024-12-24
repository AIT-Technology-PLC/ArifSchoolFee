@extends('layouts.app')

@section('title', 'Stripe Setting')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        Stripe Setting
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.stripe-settings.store') }}"
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
                            <x-forms.label for="STRIPE_SECRET_KEY">
                                Stripe Secret Key <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="STRIPE_SECRET_KEY"
                                    name="STRIPE_SECRET_KEY"
                                    type="text"
                                    placeholder="STRIPE_SECRET_KEY"
                                    value="{{ get_static_option('STRIPE_SECRET_KEY') }}"
                                />
                                <x-common.icon
                                    name="fas fa-key"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="STRIPE_SECRET_KEY" />
                            </x-forms.control>
                        </x-forms.field>
                        <x-forms.field>
                            <x-forms.label for="STRIPE_PUBLISHABLE_KEY">
                                Stripe Publishable ID <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="STRIPE_PUBLISHABLE_KEY"
                                    name="STRIPE_PUBLISHABLE_KEY"
                                    type="text"
                                    placeholder="STRIPE_PUBLISHABLE_KEY"
                                    value="{{ get_static_option('STRIPE_PUBLISHABLE_KEY') }}"
                                />
                                <x-common.icon
                                    name="fas fa-key"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="STRIPE_PUBLISHABLE_KEY" />
                            </x-forms.control>
                        </x-forms.field>
                        <x-forms.field>
                            <x-forms.label for="STRIPE_PAYMENT_MODE">
                                Stripe Payment Mode <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="STRIPE_PAYMENT_MODE"
                                    name="STRIPE_PAYMENT_MODE"
                                    type="text"
                                    placeholder="STRIPE_PAYMENT_MODE"
                                    value="{{ get_static_option('STRIPE_PAYMENT_MODE') }}"
                                />
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="STRIPE_PAYMENT_MODE" />
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

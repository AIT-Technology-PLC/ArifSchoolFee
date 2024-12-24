@extends('layouts.app')

@section('title', 'Telebirr Setting')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        Telebirr Setting
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.telebirr-settings.store') }}"
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
                            <x-forms.label for="TELEBIRR_CHECKOUT_URL">
                                Telebirr Checkour Url<sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TELEBIRR_CHECKOUT_URL"
                                    name="TELEBIRR_CHECKOUT_URL"
                                    type="text"
                                    placeholder="TELEBIRR_CHECKOUT_URL"
                                    value="{{ get_static_option('TELEBIRR_CHECKOUT_URL') }}"
                                />
                                <x-common.icon
                                    name="fas fa-link"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TELEBIRR_CHECKOUT_URL" />
                            </x-forms.control>
                        </x-forms.field>
                        <x-forms.field>
                            <x-forms.label for="TELEBIRR_CLIENT_ID">
                                Telebirr Client ID <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TELEBIRR_CLIENT_ID"
                                    name="TELEBIRR_CLIENT_ID"
                                    type="text"
                                    placeholder="TELEBIRR_CLIENT_ID"
                                    value="{{ get_static_option('TELEBIRR_CLIENT_ID') }}"
                                />
                                <x-common.icon
                                    name="fas fa-id-badge"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TELEBIRR_CLIENT_ID" />
                            </x-forms.control>
                        </x-forms.field>
                        <x-forms.field>
                            <x-forms.label for="TELEBIRR_API_KEY">
                                Telebirr Api Key <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TELEBIRR_API_KEY"
                                    name="TELEBIRR_API_KEY"
                                    type="text"
                                    placeholder="TELEBIRR_API_KEY"
                                    value="{{ get_static_option('TELEBIRR_API_KEY') }}"
                                />
                                <x-common.icon
                                    name="fas fa-key"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TELEBIRR_API_KEY" />
                            </x-forms.control>
                        </x-forms.field>
                        <x-forms.field>
                            <x-forms.label for="TELEBIRR_SECRET_KEY">
                                Telebirr Secret Key <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TELEBIRR_SECRET_KEY"
                                    name="TELEBIRR_SECRET_KEY"
                                    type="text"
                                    placeholder="TELEBIRR_SECRET_KEY"
                                    value="{{ get_static_option('TELEBIRR_SECRET_KEY') }}"
                                />
                                <x-common.icon
                                    name="fas fa-key"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TELEBIRR_SECRET_KEY" />
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

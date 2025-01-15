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
                            <x-forms.label for="TELEBIRR_BASE_URL">
                                Telebirr Base Url<sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TELEBIRR_BASE_URL"
                                    name="TELEBIRR_BASE_URL"
                                    type="text"
                                    placeholder="TELEBIRR_BASE_URL"
                                    value="{{ get_static_option('TELEBIRR_BASE_URL') }}"
                                />
                                <x-common.icon
                                    name="fas fa-link"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TELEBIRR_BASE_URL" />
                            </x-forms.control>
                        </x-forms.field>
                        <x-forms.field>
                            <x-forms.label for="TELEBIRR_MERCHANT_APP_ID">
                                Telebirr Merchant App ID <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TELEBIRR_MERCHANT_APP_ID"
                                    name="TELEBIRR_MERCHANT_APP_ID"
                                    type="text"
                                    placeholder="TELEBIRR_MERCHANT_APP_ID"
                                    value="{{ get_static_option('TELEBIRR_MERCHANT_APP_ID') }}"
                                />
                                <x-common.icon
                                    name="fas fa-id-badge"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TELEBIRR_MERCHANT_APP_ID" />
                            </x-forms.control>
                        </x-forms.field>
                        <x-forms.field>
                            <x-forms.label for="TELEBIRR_MERCHANT_CODE">
                                Telebirr Merchant Code <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TELEBIRR_MERCHANT_CODE"
                                    name="TELEBIRR_MERCHANT_CODE"
                                    type="text"
                                    placeholder="TELEBIRR_MERCHANT_CODE"
                                    value="{{ get_static_option('TELEBIRR_MERCHANT_CODE') }}"
                                />
                                <x-common.icon
                                    name="fas fa-code-branch"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TELEBIRR_MERCHANT_CODE" />
                            </x-forms.control>
                        </x-forms.field>
                        <x-forms.field>
                            <x-forms.label for="TELEBIRR_FABRIC_APP_ID">
                                Telebirr Fabric App ID <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TELEBIRR_FABRIC_APP_ID"
                                    name="TELEBIRR_FABRIC_APP_ID"
                                    type="text"
                                    placeholder="TELEBIRR_FABRIC_APP_ID"
                                    value="{{ get_static_option('TELEBIRR_FABRIC_APP_ID') }}"
                                />
                                <x-common.icon
                                    name="fas fa-key"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TELEBIRR_FABRIC_APP_ID" />
                            </x-forms.control>
                        </x-forms.field>
                        <x-forms.field>
                            <x-forms.label for="TELEBIRR_APP_SECRET">
                                Telebirr App Secret <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TELEBIRR_APP_SECRET"
                                    name="TELEBIRR_APP_SECRET"
                                    type="text"
                                    placeholder="TELEBIRR_APP_SECRET"
                                    value="{{ get_static_option('TELEBIRR_APP_SECRET') }}"
                                />
                                <x-common.icon
                                    name="fas fa-key"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TELEBIRR_APP_SECRET" />
                            </x-forms.control>
                        </x-forms.field>
                        <x-forms.field>
                            <x-forms.label for="TELEBIRR_PRIVATE_KEY">
                                Telebirr Private Key <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TELEBIRR_PRIVATE_KEY"
                                    name="TELEBIRR_PRIVATE_KEY"
                                    type="text"
                                    placeholder="TELEBIRR_PRIVATE_KEY"
                                    value="{{ get_static_option('TELEBIRR_PRIVATE_KEY') }}"
                                />
                                <x-common.icon
                                    name="fas fa-key"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TELEBIRR_PRIVATE_KEY" />
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

@extends('layouts.app')

@section('title', 'Email Setting')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        Email Setting
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.email-settings.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <x-common.success-message :message="session('successMessage')" />
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="MAIL_FROM_NAME">
                                MAIL_FROM_NAME <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="MAIL_FROM_NAME"
                                    name="MAIL_FROM_NAME"
                                    type="text"
                                    placeholder="MAIL_FROM_NAME"
                                    value="{{ get_static_option('MAIL_FROM_NAME') }}"
                                />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="MAIL_FROM_NAME" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="MAIL_FROM_ADDRESS">
                                MAIL_FROM_ADDRESS <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="MAIL_FROM_ADDRESS"
                                    name="MAIL_FROM_ADDRESS"
                                    type="text"
                                    placeholder="MAIL_FROM_ADDRESS"
                                    value="{{ get_static_option('MAIL_FROM_ADDRESS') }}"
                                />
                                <x-common.icon
                                    name="fas fa-envelope"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="MAIL_FROM_ADDRESS" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="MAIL_MAILER">
                                MAIL_MAILER <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="MAIL_MAILER"
                                    name="MAIL_MAILER"
                                    type="text"
                                    placeholder="MAIL_MAILER"
                                    value="{{ get_static_option('MAIL_MAILER') }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="MAIL_MAILER" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="MAIL_HOST">
                                MAIL_HOST <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="MAIL_HOST"
                                    name="MAIL_HOST"
                                    type="text"
                                    placeholder="MAIL_HOST"
                                    value="{{ get_static_option('MAIL_HOST') }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="MAIL_HOST" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="MAIL_PORT">
                                MAIL_PORT <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="MAIL_PORT"
                                    name="MAIL_PORT"
                                    type="number"
                                    placeholder="MAIL_PORT"
                                    value="{{ get_static_option('MAIL_PORT') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="MAIL_PORT" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="MAIL_USERNAME">
                                MAIL_USERNAME <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="MAIL_USERNAME"
                                    name="MAIL_USERNAME"
                                    type="text"
                                    placeholder="MAIL_USERNAME"
                                    value="{{ get_static_option('MAIL_USERNAME') }}"
                                />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="MAIL_USERNAME" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="MAIL_PASSWORD">
                                MAIL_PASSWORD <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="MAIL_PASSWORD"
                                    name="MAIL_PASSWORD"
                                    type="password"
                                    placeholder="MAIL_PASSWORD"
                                    value="{{ get_static_option('MAIL_PASSWORD') }}"
                                />
                                <x-common.icon
                                    name="fas fa-lock"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="MAIL_PASSWORD" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="MAIL_ENCRYPTION">
                                MAIL_ENCRYPTION <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="MAIL_ENCRYPTION"
                                    name="MAIL_ENCRYPTION"
                                    type="text"
                                    placeholder="MAIL_ENCRYPTION"
                                    value="{{ get_static_option('MAIL_ENCRYPTION') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="MAIL_ENCRYPTION" />
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

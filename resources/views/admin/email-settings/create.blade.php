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
                            <x-forms.label for="from_name">
                                From Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="from_name"
                                    name="from_name"
                                    type="text"
                                    placeholder="From Name"
                                    value="{{ $Email->from_name ?? old('from_name') }}"
                                />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="from_name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="from_mail">
                                From Mail <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="from_mail"
                                    name="from_mail"
                                    type="text"
                                    placeholder="From Mail"
                                    value="{{ $Email->from_mail ?? old('from_mail') }}"
                                />
                                <x-common.icon
                                    name="fas fa-envelope"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="from_mail" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="mail_driver">
                                Mail Driver <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="mail_driver"
                                    name="mail_driver"
                                    type="text"
                                    placeholder="Email Driver"
                                    value="{{ $Email->mail_driver ?? old('mail_driver') }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="mail_driver" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="mail_host">
                                Mail Host <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="mail_host"
                                    name="mail_host"
                                    type="text"
                                    placeholder="Mail Host"
                                    value="{{ $Email->mail_host ?? old('mail_host') }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="mail_host" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="mail_port">
                                Mail Port <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="mail_port"
                                    name="mail_port"
                                    type="number"
                                    placeholder="Mail Port"
                                    value="{{ $Email->mail_port ?? old('mail_port') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="mail_port" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="mail_username">
                                Mail Username <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="mail_username"
                                    name="mail_username"
                                    type="text"
                                    placeholder="Mail Username"
                                    value="{{ $Email->mail_username ?? old('mail_username') }}"
                                />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="mail_username" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="mail_password">
                                Mail Password <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="mail_password"
                                    name="mail_password"
                                    type="password"
                                    placeholder="Mail Password"
                                    value="{{ $Email->mail_password ?? old('mail_password') }}"
                                />
                                <x-common.icon
                                    name="fas fa-lock"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="mail_password" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="mail_encryption">
                                Mail Encryption <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="mail_encryption"
                                    name="mail_encryption"
                                    type="text"
                                    placeholder="Mail Encryption"
                                    value="{{ $Email->mail_encryption ?? old('mail_encryption') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="mail_encryption" />
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

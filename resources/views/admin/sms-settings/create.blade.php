@extends('layouts.app')

@section('title', 'Sms Setting')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        Sms Setting
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.sms-settings.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <x-common.success-message :message="session('successMessage')" />
                <div class="columns is-marginless is-multiline">
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="single_message_url">
                                Single Message Url <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="single_message_url"
                                    name="single_message_url"
                                    type="text"
                                    placeholder="Single Message Url"
                                    value="{{ $Sms->single_message_url ?? old('single_message_url') }}"
                                />
                                <x-common.icon
                                    name="fas fa-message"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="single_message_url" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="bulk_message_url">
                                Bulk Message Url <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="bulk_message_url"
                                    name="bulk_message_url"
                                    type="text"
                                    placeholder="Bulk Message Url"
                                    value="{{ $Sms->bulk_message_url ?? old('bulk_message_url') }}"
                                />
                                <x-common.icon
                                    name="fas fa-message"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="bulk_message_url" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="security_message_url">
                                Security Message Url <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="security_message_url"
                                    name="security_message_url"
                                    type="text"
                                    placeholder="Security Message Url"
                                    value="{{ $Sms->security_message_url ?? old('security_message_url') }}"
                                />
                                <x-common.icon
                                    name="fas fa-message"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="security_message_url" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="callback">
                                Callback Url <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="callback"
                                    name="callback"
                                    type="text"
                                    placeholder="Callback Url"
                                    value="{{ $Sms->callback ?? old('callback') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="callback" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="create_callback">
                                Create Callback Url <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="create_callback"
                                    name="create_callback"
                                    type="text"
                                    placeholder="Create Callback Url"
                                    value="{{ $Sms->create_callback ?? old('create_callback') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="create_callback" />
                            </x-forms.control>
                        </x-forms.field>
                    </div> 
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="token">
                                Token <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="token"
                                    name="token"
                                    type="text"
                                    placeholder="Token"
                                    value="{{ $Sms->token ?? old('token') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="token" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="from">
                                From <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="from"
                                    name="from"
                                    type="text"
                                    placeholder="From"
                                    value="{{ $Sms->from ?? old('from') }}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="from" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="sender">
                                Sender Name <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="sender"
                                    name="sender"
                                    type="text"
                                    placeholder="Sender Name"
                                    value="{{ $Sms->sender ?? old('sender') }}"
                                />
                                <x-common.icon
                                    name="fas fa-user-tie"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="sender" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="compaign">
                                Compaign Name <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="compaign"
                                    name="compaign"
                                    type="text"
                                    placeholder="Compaign Name"
                                    value="{{ $Sms->compaign ?? old('compaign') }}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="compaign" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>                
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="space_After">
                                Space Before <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="space_before"
                                    name="space_before"
                                    type="number"
                                    placeholder="Space Before"
                                    value="{{ $Sms->space_before ?? old('space_before') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="space_before" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="space_after">
                                Space After <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="space_after"
                                    name="space_after"
                                    type="number"
                                    placeholder="Space After"
                                    value="{{ $Sms->space_after ?? old('space_after') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="space_after" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="time_to_live">
                                Time To live <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="time_to_live"
                                    name="time_to_live"
                                    type="number"
                                    placeholder="Time To live"
                                    value="{{ $Sms->time_to_live ?? old('time_to_live') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="time_to_live" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="code_length">
                                Code length <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="code_length"
                                    name="code_length"
                                    type="number"
                                    placeholder="Code length"
                                    value="{{ $Sms->code_length ?? old('code_length') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="code_length" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="code_type">
                                Code Type <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="code_type"
                                    name="code_type"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Code Type
                                    </option>
                                    <option
                                        value="0"
                                        @selected($Sms->code_type ?? old('code_type') == '0')
                                    >
                                     Number 
                                    </option>
                                    <option
                                        value="1"
                                        @selected($Sms->code_type ?? old('code_type') == '1')
                                    >
                                     Alphabet 
                                    </option>
                                    <option
                                        value="2"
                                        @selected($Sms->code_type ?? old('code_type') == '2')
                                    >
                                      Alpha Numeric 
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="code_type" />
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

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
                            <x-forms.label for="AFROMESSAGE_SINGLE_MESSAGE_URL">
                                SINGLE_MESSAGE_URL <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="AFROMESSAGE_SINGLE_MESSAGE_URL"
                                    name="AFROMESSAGE_SINGLE_MESSAGE_URL"
                                    type="text"
                                    placeholder="SINGLE_MESSAGE_URL"
                                    value="{{ get_static_option('AFROMESSAGE_SINGLE_MESSAGE_URL') }}"
                                />
                                <x-common.icon
                                    name="fas fa-message"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="AFROMESSAGE_SINGLE_MESSAGE_URL" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="AFROMESSAGE_BULK_MESSAGE_URL">
                                BULK_MESSAGE_URL <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="AFROMESSAGE_BULK_MESSAGE_URL"
                                    name="AFROMESSAGE_BULK_MESSAGE_URL"
                                    type="text"
                                    placeholder="BULK_MESSAGE_URL"
                                    value="{{ get_static_option('AFROMESSAGE_BULK_MESSAGE_URL') }}"
                                />
                                <x-common.icon
                                    name="fas fa-message"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="AFROMESSAGE_BULK_MESSAGE_URL" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="AFROMESSAGE_SECURITY_MESSAGE_URL">
                                SECURITY_MESSAGE_URL <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="AFROMESSAGE_SECURITY_MESSAGE_URL"
                                    name="AFROMESSAGE_SECURITY_MESSAGE_URL"
                                    type="text"
                                    placeholder="SECURITY_MESSAGE_URL"
                                    value="{{ get_static_option('AFROMESSAGE_SECURITY_MESSAGE_URL') }}"
                                />
                                <x-common.icon
                                    name="fas fa-message"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="AFROMESSAGE_SECURITY_MESSAGE_URL" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="AFROMESSAGE_CALLBACK">
                                CALLBACK_URL <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="AFROMESSAGE_CALLBACK"
                                    name="AFROMESSAGE_CALLBACK"
                                    type="text"
                                    placeholder="CALLBACK_URL"
                                    value="{{ get_static_option('AFROMESSAGE_CALLBACK') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="AFROMESSAGE_CALLBACK" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="AFROMESSAGE_CREATE_CALLBACK">
                                CREATE_CALLBACK_URL <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="AFROMESSAGE_CREATE_CALLBACK"
                                    name="AFROMESSAGE_CREATE_CALLBACK"
                                    type="text"
                                    placeholder="CREATE_CALLBACK_URL"
                                    value="{{ get_static_option('AFROMESSAGE_CREATE_CALLBACK') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="AFROMESSAGE_CREATE_CALLBACK" />
                            </x-forms.control>
                        </x-forms.field>
                    </div> 
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="AFROMESSAGE_STATUS_CALLBACK">
                                STATUS_CALLBACK_URL <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="AFROMESSAGE_STATUS_CALLBACK"
                                    name="AFROMESSAGE_STATUS_CALLBACK"
                                    type="text"
                                    placeholder="STATUS_CALLBACK_URL"
                                    value="{{ get_static_option('AFROMESSAGE_STATUS_CALLBACK') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="AFROMESSAGE_STATUS_CALLBACK" />
                            </x-forms.control>
                        </x-forms.field>
                    </div> 
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="AFROMESSAGE_TOKEN">
                                TOKEN <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="AFROMESSAGE_TOKEN"
                                    name="AFROMESSAGE_TOKEN"
                                    type="text"
                                    placeholder="TOKEN"
                                    value="{{ get_static_option('AFROMESSAGE_TOKEN') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="AFROMESSAGE_TOKEN" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="AFROMESSAGE_FROM">
                                FROM <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="AFROMESSAGE_FROM"
                                    name="AFROMESSAGE_FROM"
                                    type="text"
                                    placeholder="FROM"
                                    value="{{ get_static_option('AFROMESSAGE_FROM') }}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="AFROMESSAGE_FROM" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="AFROMESSAGE_SENDER">
                                SENDER_NAME <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="AFROMESSAGE_SENDER"
                                    name="AFROMESSAGE_SENDER"
                                    type="text"
                                    placeholder="SENDER_NAME"
                                    value="{{ get_static_option('AFROMESSAGE_SENDER') }}"
                                />
                                <x-common.icon
                                    name="fas fa-user-tie"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="AFROMESSAGE_SENDER" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="AFROMESSAGE_CAMPAIGN_NAME">
                                CAMPAIGN_NAME <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="AFROMESSAGE_CAMPAIGN_NAME"
                                    name="AFROMESSAGE_CAMPAIGN_NAME"
                                    type="text"
                                    placeholder="CAMPAIGN_NAME"
                                    value="{{ get_static_option('AFROMESSAGE_CAMPAIGN_NAME') }}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="AFROMESSAGE_CAMPAIGN_NAME" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>                
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="SPACES_BEFORE">
                                SPACES_BEFORE <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="SPACES_BEFORE"
                                    name="SPACES_BEFORE"
                                    type="number"
                                    placeholder="SPACES_BEFORE"
                                    value="{{ get_static_option('SPACES_BEFORE') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="SPACES_BEFORE" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="SPACES_AFTER">
                                SPACES_AFTER <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="SPACES_AFTER"
                                    name="SPACES_AFTER"
                                    type="number"
                                    placeholder="SPACES_AFTER"
                                    value="{{ get_static_option('SPACES_AFTER') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="SPACES_AFTER" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="TIME_TO_LIVE">
                                TIME_TO_LIVE <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="TIME_TO_LIVE"
                                    name="TIME_TO_LIVE"
                                    type="number"
                                    placeholder="TIME_TO_LIVE"
                                    value="{{ get_static_option('TIME_TO_LIVE') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="TIME_TO_LIVE" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="CODE_LENGTH">
                                CODE_LENGTH <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="CODE_LENGTH"
                                    name="CODE_LENGTH"
                                    type="number"
                                    placeholder="CODE_LENGTH"
                                    value="{{ get_static_option('CODE_LENGTH') }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="CODE_LENGTH" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="CODE_TYPE">
                                CODE_TYPE <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="CODE_TYPE"
                                    name="CODE_TYPE"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Code Type
                                    </option>
                                    <option
                                        value="0"
                                        @selected(get_static_option('CODE_LENGTH') == '0')
                                    >
                                     Number 
                                    </option>
                                    <option
                                        value="1"
                                        @selected(get_static_option('CODE_LENGTH') == '1')
                                    >
                                     Alphabet 
                                    </option>
                                    <option
                                        value="2"
                                        @selected(get_static_option('CODE_LENGTH') == '2')
                                    >
                                      Alpha Numeric 
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="CODE_TYPE" />
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

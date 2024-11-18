@extends('layouts.app')

@section('title', 'Notification Setting')

@section('content')
<x-common.content-wrapper>
    <x-content.header>
        <x-slot name="header">
            <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                <x-common.icon name="fas fa-bell" />
                <span>
                    Notification and Alert
                </span>
            </span>
        </x-slot>
    </x-content.header>
    <form
        id="formOne"
        action="{{ route('notification-settings.update', userCompany()->id) }}"
        method="POST"
        enctype="multipart/form-data"
        novalidate
    >
    @csrf
    @method('PATCH')
        <x-content.main>
            <x-common.success-message :message="session('successMessage')" />
            <div class="columns is-marginless is-multiline">
                <div class="column is-4">
                    <x-forms.field>
                        <x-forms.label for="can_send_payment_reminder">
                            Payment Reminder <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.control class="has-icons-left">
                            <x-forms.select
                                class="is-fullwidth"
                                id="can_send_payment_reminder"
                                name="can_send_payment_reminder"
                            >
                                <option
                                    value="1"
                                    @selected($school->canSendPaymentReminder())
                                >Enabled</option>
                                <option
                                    value="0"
                                    @selected(!$school->canSendPaymentReminder())
                                >Disabled</option>
                            </x-forms.select>
                            <x-common.icon
                                name="fas fa-sort"
                                class="is-small is-left"
                            />
                        </x-forms.control>
                    </x-forms.field>
                </div>
                <div class="column is-4">
                    <x-forms.field>
                        <x-forms.label for="can_send_sms_alert">
                            SMS Alert <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.control class="has-icons-left">
                            <x-forms.select
                                class="is-fullwidth"
                                id="can_send_sms_alert"
                                name="can_send_sms_alert"
                            >
                                <option
                                    value="1"
                                    @selected($school->canSendSmsAlert())
                                >Enabled</option>
                                <option
                                    value="0"
                                    @selected(!$school->canSendSmsAlert())
                                >Disabled</option>
                            </x-forms.select>
                            <x-common.icon
                                name="fas fa-sort"
                                class="is-small is-left"
                            />
                        </x-forms.control>
                    </x-forms.field>
                </div>
                <div class="column is-4">
                    <x-forms.field>
                        <x-forms.label for="can_send_email_notification">
                            Email Notification <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.control class="has-icons-left">
                            <x-forms.select
                                class="is-fullwidth"
                                id="can_send_email_notification"
                                name="can_send_email_notification"
                            >
                                <option
                                    value="1"
                                    @selected($school->canSendEmailNotification())
                                >Enabled</option>
                                <option
                                    value="0"
                                    @selected(!$school->canSendEmailNotification())
                                >Disabled</option>
                            </x-forms.select>
                            <x-common.icon
                                name="fas fa-sort"
                                class="is-small is-left"
                            />
                        </x-forms.control>
                    </x-forms.field>
                </div>
                <div class="column is-4">
                    <x-forms.field>
                        <x-forms.label for="can_send_push_notification">
                            Push Notification <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.control class="has-icons-left">
                            <x-forms.select
                                class="is-fullwidth"
                                id="can_send_push_notification"
                                name="can_send_push_notification"
                            >
                                <option
                                    value="1"
                                    @selected($school->canSendPushNotification())
                                >Enabled</option>
                                <option
                                    value="0"
                                    @selected(!$school->canSendPushNotification())
                                >Disabled</option>
                            </x-forms.select>
                            <x-common.icon
                                name="fas fa-sort"
                                class="is-small is-left"
                            />
                        </x-forms.control>
                    </x-forms.field>
                </div>
                <div class="column is-4">
                    <x-forms.field>
                        <x-forms.label for="can_send_system_alert">
                            System Generated Alert <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.control class="has-icons-left">
                            <x-forms.select
                                class="is-fullwidth"
                                id="can_send_system_alert"
                                name="can_send_system_alert"
                            >
                                <option
                                    value="1"
                                    @selected($school->canSendSystemAlert())
                                >Enabled</option>
                                <option
                                    value="0"
                                    @selected(!$school->canSendSystemAlert())
                                >Disabled</option>
                            </x-forms.select>
                            <x-common.icon
                                name="fas fa-sort"
                                class="is-small is-left"
                            />
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

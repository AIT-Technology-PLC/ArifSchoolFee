@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Notificatoins"
                :amount="$totalNotifications"
                icon="fas fa-bell"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalReadNotifications"
                border-color="#3d8660"
                text-color="text-green"
                label="Seen"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalUnreadNotifications"
                border-color="#863d63"
                text-color="text-purple"
                label="Unseen"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header
            title="Notifications"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if ($totalUnreadNotifications)
                    <x-common.dropdown-item>
                        <form
                            x-data="swal('mark all as read', 'mark all notifications as read')"
                            class="is-inline"
                            action="{{ route('notifications.markAllAsRead') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            novalidate
                            @submit.prevent="open"
                        >
                            @csrf
                            @method('PATCH')
                            <x-common.button
                                tag="button"
                                mode="button"
                                icon="fas fa-check-double"
                                label="Mark all as read"
                                class="has-text-weight-medium is-small text-softblue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                x-ref="submitButton"
                            />
                        </form>
                    </x-common.dropdown-item>
                @endif
                @if ($totalNotifications)
                    <x-common.dropdown-item>
                        <x-common.transaction-button
                            :route="route('notifications.delete_all')"
                            action="delete"
                            intention="delete all of the notifications"
                            icon="fas fa-trash"
                            label="Delete All"
                            class="has-text-weight-medium is-small text-softblue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endif
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-datatables.filter filters="'status'">
                <div class="columns is-marginless is-vcentered">
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.status"
                                    x-on:change="add('status')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Statuses
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Unseen', 'Seen'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

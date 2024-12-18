@extends('layouts.app')

@section('title', 'Admins')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 dp-lr-0">
            <x-common.total-model
                model="Admin Account"
                box-color="bg-softblue"
                :amount="$totalAdmins"
                icon="fas fa-user-shield"
            />
        </div>
        <div class="column is-4 dp-lr-0">
            <x-common.total-model
                model="Call Center"
                box-color="bg-lightblue"
                :amount="$totalCallCenterUsers"
                icon="fas fa-headset"
            />
        </div>
        <div class="column is-4 dp-lr-0">
            <x-common.total-model
                model="Bank User"
                box-color="bg-green"
                :amount="$totalBankUsers"
                icon="fas fa-user-tie"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Users
                </h1>
            </x-slot>
            <x-common.button
                tag="a"
                href="{{ route('admin.users.create') }}"
                mode="button"
                icon="fas fa-plus-circle"
                label="Create Account"
                class="btn-softblue is-outlined is-small"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
              <x-datatables.filter filters="'user_type', 'status', 'bank'">
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
                                    @foreach (['Active', 'Inactive'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.user_type"
                                    x-on:change="add('user_type')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        User Type
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Admin', 'Call Center', 'Bank'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.bank"
                                    x-on:change="add('bank')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Banks
                                    </option>
                                    <option value="all"> All </option>
                                    @if (old('bank_name'))
                                        <option
                                            value="{{ str()->lower(old('bank_name')) }}"
                                            selected
                                        >
                                            {{ old('bank_name') }}
                                        </option>
                                    @endif
                                    @include('lists.banks')
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

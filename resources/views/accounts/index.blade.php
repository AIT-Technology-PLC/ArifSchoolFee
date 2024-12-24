@extends('layouts.app')

@section('title', 'Accounts')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Total Account"
                :amount="$totalAccount"
                icon="fas fa-wallet"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Active"
                box-color="bg-lightblue"
                :amount="$totalActiveAccount"
                icon="fas fa-check"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="InActive"
                box-color="bg-red"
                :amount="$totalInActiveAccount"
                icon="fas fa-ban"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Accounts">
            @can('Create Account')
                <x-common.button
                    tag="a"
                    href="{{ route('accounts.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Account"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
            <x-common.fail-message :message="session('failedMessage')" />
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
                                    @foreach (['Active', 'Inactive'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            <div> {{ $dataTable->table() }} </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

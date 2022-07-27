@extends('layouts.app')

@section('title', 'Earnings')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Earnings"
                :amount="$totalEarnings"
                icon="fas fa-hand-holding-dollar"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalApproved"
                border-color="#3d8660"
                text-color="text-green"
                label="Approved"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotApproved"
                border-color="#86843d"
                text-color="text-gold"
                label="Waiting Approval"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Earnings">
            @can('Create Earning')
                <x-common.button
                    tag="a"
                    href="{{ route('earnings.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Earning"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
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
                                    @foreach (['Approved', 'Waiting Approval'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
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

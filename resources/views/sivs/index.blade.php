@extends('layouts.app')

@section('title', 'Store Issue Vouchers')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Store Issue Vouchers"
                :amount="$totalSivs"
                icon="fas fa-file-export"
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
                border-color="#863d63"
                text-color="text-purple"
                label="Waiting Approval"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Store Issue Vouchers">
            @can('Create SIV')
                <x-common.button
                    tag="a"
                    href="{{ route('sivs.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create SIV"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-datatables.filter filters="'branch', 'status'">
                <div class="columns is-marginless is-vcentered">
                    @if (auth()->user()->getAllowedWarehouses('transactions')->count() > 1)
                        <div class="column is-3 p-lr-0 pt-0">
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id=""
                                        name=""
                                        class="is-size-7-mobile is-fullwidth"
                                        x-model="filters.branch"
                                        x-on:change="add('branch')"
                                    >
                                        <option
                                            disabled
                                            selected
                                            value=""
                                        >
                                            Branches
                                        </option>
                                        <option value="all"> All </option>
                                        @foreach (auth()->user()->getAllowedWarehouses('transactions')
        as $warehouse)
                                            <option value="{{ $warehouse->id }}"> {{ $warehouse->name }} </option>
                                        @endforeach
                                    </x-forms.select>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
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
                                    @foreach (['Waiting Approval', 'Approved'] as $status)
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

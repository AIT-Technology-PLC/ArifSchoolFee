@extends('layouts.app')

@section('title', 'Warehouses')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Warehouses"
                :amount="$totalWarehouses"
                icon="fas fa-warehouse"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalActiveWarehouses"
                border-color="#3d8660"
                text-color="text-green"
                label="Active"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalInActiveWarehouses"
                border-color="#863d63"
                text-color="text-purple"
                label="Inactive"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Warehouses">
            @can('Import Warehouse')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Warehouses"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create Warehouse')
                <x-common.button
                    tag="a"
                    href="{{ route('warehouses.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Warehouse"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="session('limitReachedMessage') ?? (count($errors->all()) ? $errors->all() : null)" />
            <x-datatables.filter filters="'branch', 'status'">
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
    @can('Import Warehouse')
        <x-common.import
            title="Import Warehouses"
            action="{{ route('warehouses.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@extends('layouts.app')

@section('title', 'Cost Updates')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="cost updates"
                :amount="$totalCostUpdates"
                icon="fas fa-tag"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalApproved"
                border-color="#3d8660"
                text-color="text-green"
                label="Approved"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotApproved"
                border-color="#863d63"
                text-color="text-purple"
                label="Waiting Approval"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalRejected"
                border-color="#863d63"
                text-color="text-purple"
                label="Rejected"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Cost Updates">
            @can('Import Cost Update')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Cost Update"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create Cost Update')
                <x-common.button
                    tag="a"
                    href="{{ route('cost-updates.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Cost Update"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
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
                                    @foreach (['Waiting Approval', 'Approved', 'Rejected'] as $status)
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
    @can('Import Cost Update')
        <x-common.import
            title="Import Cost Update"
            action="{{ route('cost-updates.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

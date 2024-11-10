@extends('layouts.app')

@section('title', 'Branches')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Branches"
                :amount="$totalBranches"
                icon="fas fa-code-branch"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Active"
                box-color="bg-green"
                :amount="$totalActiveBranches"
                icon="fas fa-check"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="InActive"
                box-color="bg-purple"
                :amount="$totalInActiveBranches"
                icon="fas fa-ban"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Branches">
            @can('Import Branch')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Branches"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
            @can('Create Branch')
                <x-common.button
                    tag="a"
                    href="{{ route('branches.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Branch"
                    class="btn-blue is-outlined is-small"
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
    @can('Import Branch')
        <x-common.import
            title="Import Branch"
            action="{{ route('branches.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

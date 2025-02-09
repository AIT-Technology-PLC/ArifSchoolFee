@extends('layouts.app')

@section('title', 'Fee Type')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Fee Type
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-layer-group" />
                        <span>
                            {{ number_format($totalTypes) }} {{ str()->plural('type', $totalTypes) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Fee Type')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Types"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
            @can('Create Fee Type')
                <x-common.button
                    tag="a"
                    href="{{ route('fee-types.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Type"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="session('failedMessage') ??  (count($errors->all()) ? $errors->all() : null)" />
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Fee Type')
        <x-common.import
            title="Import Type"
            action="{{ route('fee-types.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@extends('layouts.app')

@section('title', 'Class')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Class
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-layer-group" />
                        <span>
                            {{ number_format($totalClasses) }} {{ str()->plural('classes', $totalClasses) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Class')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Classes"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
            @can('Create Class')
                <x-common.button
                    tag="a"
                    href="{{ route('school-classes.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Class"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="session('failedMessage') ?? (count($errors->all()) ? $errors->all() : null)" />
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Class')
        <x-common.import
            title="Import Class"
            action="{{ route('classes.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

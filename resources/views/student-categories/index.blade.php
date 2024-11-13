@extends('layouts.app')

@section('title', 'Student Category')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Student Category
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-layer-group" />
                        <span>
                            {{ number_format($totalCategories) }} {{ str()->plural('category', $totalCategories) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Student Category')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Categories"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
            @can('Create Student Category')
                <x-common.button
                    tag="a"
                    href="{{ route('student-categories.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Category"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="session('failedMessage')" />
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Student Category')
        <x-common.import
            title="Import Category"
            action="{{ route('student-categories.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

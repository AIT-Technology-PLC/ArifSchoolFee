@extends('layouts.app')

@section('title', 'Academic Year')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Academic Year
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-sort" />
                        <span>
                            {{ number_format($totalAcademicYears) }} {{ str()->plural('academic year'), $totalAcademicYears }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Academic Year')
                <x-common.button
                    tag="a"
                    href="{{ route('academic-years.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Academic Year"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
            <x-common.fail-message :message="session('failedMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

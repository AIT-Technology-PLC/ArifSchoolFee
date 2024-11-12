@extends('layouts.app')

@section('title', 'Student Group')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Student Group
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-layer-group" />
                        <span>
                            {{ number_format($totalGroups) }} {{ str()->plural('group', $totalGroups) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Student Category')
                <x-common.button
                    tag="a"
                    href="{{ route('student-groups.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Group"
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
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

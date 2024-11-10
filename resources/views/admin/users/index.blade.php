@extends('layouts.app')

@section('title', 'Admins')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-blue has-text-weight-medium is-size-5">
                    Admins
                    <span class="tag bg-blue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-user-group" />
                        <span>
                            {{ number_format($totalUsers) }} {{ str()->plural('admin', $totalUsers) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            <x-common.button
                tag="a"
                href="{{ route('admin.users.create') }}"
                mode="button"
                icon="fas fa-plus-circle"
                label="Create Admin Account"
                class="btn-softblue is-outlined is-small"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

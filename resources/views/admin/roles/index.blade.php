@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Total Roles"
                box-color="bg-softblue"
                :amount="$totalRoles"
                icon="fas fa-user-shield"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Total Permissions"
                box-color="bg-red"
                :amount="$totalRoles"
                icon="fas fa-key"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Roles">
            <x-common.button
                tag="a"
                href="{{ route('admin.roles.create') }}"
                mode="button"
                icon="fas fa-plus-circle"
                label="Create Role"
                class="btn-blue is-outlined is-small"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted') ?? session('imported')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

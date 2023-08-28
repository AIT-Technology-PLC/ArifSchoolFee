@extends('layouts.app')

@section('title', 'Custom Fields')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Custom Fields">
            @can('Create Custom Field')
                <x-common.button
                    tag="a"
                    href="{{ route('custom-fields.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Custom Field"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="session('failedMessage') ?? (count($errors->all()) ? $errors->all() : null)" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

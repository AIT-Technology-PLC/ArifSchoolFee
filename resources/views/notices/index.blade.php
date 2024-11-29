@extends('layouts.app')

@section('title', 'Notice Board')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="All Notices">
            @can('Create Notice')
                <x-common.button
                    tag="a"
                    href="{{ route('notices.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Add Notice"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@extends('layouts.app')

@section('title', 'Subscription History')

@section('content')
        <x-common.content-wrapper>
        <x-content.header
            title="Subscription History"
            is-mobile
        >
        </x-content.header>
        <x-content.footer>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

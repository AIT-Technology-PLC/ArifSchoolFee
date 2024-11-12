@extends('layouts.app')

@section('title', 'User Logs')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
        <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-history" />
                    <span>
                        User Logs
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

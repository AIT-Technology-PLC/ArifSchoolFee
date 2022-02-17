@extends('layouts.app')

@section('title', 'History of ' . $product->name . ' in ' . $warehouse->name)

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="History of {{ $product->name }} in {{ $warehouse->name }}">
        </x-content.header>
        <x-content.footer>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

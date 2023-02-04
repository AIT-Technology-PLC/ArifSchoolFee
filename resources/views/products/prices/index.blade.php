@extends('layouts.app')

@section('title', $product->name . ' Price ')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="{{ $product->code ? str($product->name)->append(' (', $product->code, ')') : $product->name }}" />
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

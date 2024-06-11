@extends('layouts.app')

@section('title', $product->name . ' Price ')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="{{ $product->code ? str($product->name)->append(' - ', $product->code) : $product->name }}">
            @if (!is_null($price?->id))
                @can('Update Price')
                    <x-common.button
                        tag="a"
                        href="{{ route('prices.edit', $price->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit Prices"
                        class="btn-green is-outlined is-small"
                    />
                @endcan
            @endif
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

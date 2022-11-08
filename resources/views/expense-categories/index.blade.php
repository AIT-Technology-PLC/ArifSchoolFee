@extends('layouts.app')

@section('title', 'Expense Categories')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Expense Categories
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fa-solid fa-money-bill-trend-up" />
                        <span>
                            {{ number_format($totalExpenseCategories) }} {{ str()->plural('Category', $totalExpenseCategories) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Expense')
                <x-common.button
                    tag="a"
                    href="{{ route('expense-categories.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Category"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

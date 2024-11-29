@extends('layouts.app')

@section('title', 'Fee Collection')

@section('content')
    <x-common.content-wrapper class="mb-5">
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-user-graduate" />
                        <span>
                            Student Fees
                        </span>
                    </span>
                </h1>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            <div class="columns is-marginless is-multiline is-mobile">
                <div class="column is-6-mobile is-6-tablet is-3-desktop">
                    <p class="has-text-grey is-size-7 is-uppercase">
                        <span class="icon">
                            <i class="fas fa-user"></i>
                        </span>
                        <span>
                            {{ $collectFee->first_name }}
                        </span>
                    </p>
                    <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                        Name
                    </p>
                </div>
                <div class="column is-6-mobile is-6-tablet is-3-desktop">
                    <p class="has-text-grey is-size-7 is-uppercase">
                        <span class="icon">
                            <i class="fas fa-user"></i>
                        </span>
                        <span>
                            {{ $collectFee->father_name ?? 'N/A' }}
                        </span>
                    </p>
                    <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                        Father's Name
                    </p>
                </div>
                <div class="column is-6-mobile is-6-tablet is-3-desktop">
                    <p class="has-text-grey is-size-7 is-uppercase">
                        <span class="icon">
                            <i class="fas fa-code-branch"></i>
                        </span>
                        <span>
                            {{ $collectFee->warehouse->name ?? 'N/A' }}
                        </span>
                    </p>
                    <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                        Branch
                    </p>
                </div>
                <div class="column is-6-mobile is-6-tablet is-3-desktop">
                    <p class="has-text-grey is-size-7 is-uppercase">
                        <span class="icon">
                            <i class="fas fa-heading"></i>
                        </span>
                        <span>
                            {{ $collectFee->schoolClass->name }} ({{ $collectFee->section->name }})
                        </span>
                    </p>
                    <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                        Class Section
                    </p>
                </div>
                <div class="column is-6-mobile is-6-tablet is-3-desktop">
                    <p class="has-text-grey is-size-7 is-uppercase">
                        <span class="icon">
                            <i class="fas fa-phone"></i>
                        </span>
                        <span>
                            {{ $collectFee->phone ?? 'N/A' }}
                        </span>
                    </p>
                    <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                        Phone
                    </p>
                </div>
                <div class="column is-6-mobile is-6-tablet is-3-desktop">
                    <p class="has-text-grey is-size-7 is-uppercase">
                        <span class="icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <span>
                            {{ $collectFee->email ?? 'N/A' }}
                        </span>
                    </p>
                    <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                        Email
                    </p>
                </div>
                <div class="column is-6-mobile is-6-tablet is-3-desktop">
                    <p class="has-text-grey is-size-7 is-uppercase">
                        <span class="icon">
                            <i class="fas fa-sort"></i>
                        </span>
                        <span>
                            {{ $collectFee->studentCategory->name }} ({{ is_null($collectFee->studentCategory->description) ? 'N/A' : str($collectFee->studentCategory->description)->stripTags()->limit(20) }})
                        </span>
                    </p>
                    <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                        Category
                    </p>
                </div>
                <div class="column is-6-mobile is-6-tablet is-3-desktop">
                    <p class="has-text-grey is-size-7 is-uppercase">
                        <span class="icon">
                            <i class="fas fa-hashtag"></i>
                        </span>
                        <span>
                            {{ $collectFee->code }}
                        </span>
                    </p>
                    <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                        Admission No
                    </p>
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper>
        <x-content.header title="Add Fees">
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            <div>  {{ $dataTable->table() }} </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
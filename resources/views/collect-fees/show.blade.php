@extends('layouts.app')

@section('title', 'Fee Collection')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-12">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-softblue has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-user-graduate"></i>
                        </span>
                        <span>
                           Student Fees
                        </span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <div class="columns is-marginless is-multiline is-mobile">
                    <div class="column is-6-mobile is-6-tablet is-3-desktop">
                        <p class="has-text-grey is-size-7 is-uppercase">
                            <span class="icon">
                                <i class="fas fa-user"></i>
                            </span>
                            <span>
                                Name
                            </span>
                        </p>
                        <p class="has-text-weight-bold text-softblue ml-1">
                            {{ $collectFee->first_name }}
                        </p>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-3-desktop">
                        <p class="has-text-grey is-size-7 is-uppercase">
                            <span class="icon">
                                <i class="fas fa-user"></i>
                            </span>
                            <span>
                                Father's Name
                            </span>
                        </p>
                        <p class="has-text-weight-bold text-softblue ml-1">
                            {{ $collectFee->father_name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-3-desktop">
                        <p class="has-text-grey is-size-7 is-uppercase">
                            <span class="icon">
                                <i class="fas fa-code-branch"></i>
                            </span>
                            <span>
                                Branch
                            </span>
                        </p>
                        <p class="has-text-weight-bold text-softblue ml-1">
                            {{ $collectFee->warehouse->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-3-desktop">
                        <p class="has-text-grey is-size-7 is-uppercase">
                            <span class="icon">
                                <i class="fas fa-heading"></i>
                            </span>
                            <span>
                               Class Section
                            </span>
                        </p>
                        <p class="has-text-weight-bold text-softblue ml-1">
                            {{ $collectFee->schoolClass->name }} ({{ $collectFee->section->name }})
                        </p>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-3-desktop">
                        <p class="has-text-grey is-size-7 is-uppercase">
                            <span class="icon">
                                <i class="fas fa-phone"></i>
                            </span>
                            <span>
                               Phone
                            </span>
                        </p>
                        <p class="has-text-weight-bold text-softblue ml-1">
                            {{ $collectFee->phone ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-3-desktop">
                        <p class="has-text-grey is-size-7 is-uppercase">
                            <span class="icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <span>
                               Email
                            </span>
                        </p>
                        <p class="has-text-weight-bold text-softblue ml-1">
                            {{ $collectFee->email ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-3-desktop">
                        <p class="has-text-grey is-size-7 is-uppercase">
                            <span class="icon">
                                <i class="fas fa-sort"></i>
                            </span>
                            <span>
                               Category
                            </span>
                        </p>
                        <p class="has-text-weight-bold text-softblue ml-1">
                            {{ $collectFee->studentCategory->name }} ({{ is_null($collectFee->studentCategory->description) ? 'N/A' : str($collectFee->studentCategory->description)->stripTags()->limit(20) }})
                        </p>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-3-desktop">
                        <p class="has-text-grey is-size-7 is-uppercase">
                            <span class="icon">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            <span>
                               Admission No
                            </span>
                        </p>
                        <p class="has-text-weight-bold text-softblue ml-1">
                            {{ $collectFee->code }}
                        </p>
                    </div>
                </div>
            </x-content.footer>
        </div>
    </div>
@endsection

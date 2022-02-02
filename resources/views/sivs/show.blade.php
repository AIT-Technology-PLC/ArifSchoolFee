@extends('layouts.app')

@section('title')
    SIV Details
@endsection

@section('content')
    <div class="box mt-3 mx-3 m-lr-0">
        <div class="columns is-marginless is-multiline">
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-file-invoice"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $siv->code ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                SIV No
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $siv->issued_to ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Issued To
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-calendar-day"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $siv->issued_on->toFormattedDateString() ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Issued On
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-question"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $siv->purpose ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Purpose
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-hashtag"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $siv->ref_num ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Ref N<u>o</u>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $siv->received_by ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Receiver Name
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $siv->delivered_by ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Delivered By
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div>
                    <div class="columns is-marginless is-vcentered text-green">
                        <div class="column">
                            <div class="has-text-weight-bold">
                                Details
                            </div>
                            <div class="is-size-7 mt-3">
                                {!! $siv->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <div class="level">
                <div class="level-left">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <h1 class="title text-green has-text-weight-medium is-size-5">
                                SIV Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            @if ($siv->isApproved())
                                <a
                                    class="button is-small bg-purple has-text-white is-hidden-mobile  "
                                    href="{{ route('sivs.print', $siv->id) }}"
                                    target="_blank"
                                >
                                    <span class="icon">
                                        <i class="fas fa-print"></i>
                                    </span>
                                    <span>
                                        Print
                                    </span>
                                </a>
                            @endif
                            <a
                                href="{{ route('sivs.edit', $siv->id) }}"
                                class="button is-small bg-green has-text-white"
                            >
                                <span class="icon">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <span>
                                    Edit
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <x-common.fail-message :message="session('failedMessage')" />
            @if ($siv->isApproved())
                <x-common.success-message message="This SIV has been approved successfully." />
            @elseif (!$siv->isApproved())
                @can('Approve SIV')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            This SIV is not approved.
                            <br>
                            Click on the button below to approve this SIV.
                        </p>
                        <form
                            x-data="swal('approve', 'approve this SIV')"
                            action="{{ route('sivs.approve', $siv->id) }}"
                            method="post"
                            novalidate
                            @submit.prevent="open"
                        >
                            @csrf
                            <button
                                class="button bg-purple has-text-white mt-5 is-size-7-mobile"
                                x-ref="submitButton"
                            >
                                <span class="icon">
                                    <i class="fas fa-signature"></i>
                                </span>
                                <span>
                                    Approve SIV
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <x-common.fail-message message="This SIV is not approved." />
                @endcan
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

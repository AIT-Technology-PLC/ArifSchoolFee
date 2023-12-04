@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="level mx-3 m-lr-0">
        <div class="level-left">
            <div class="level-item is-justify-content-left">
                <div class="heading text-green is-size-5 is-size-6-mobile">
                    Dashboard
                </div>
            </div>
        </div>
        <div class="level-right m-top-0">
            <div class="level-item is-justify-content-left">
                <h1 class="heading text-green is-size-5 is-size-6-mobile has-text-weight-bold">
                    {{ now()->toDayDateTimeString() }}
                </h1>
            </div>
        </div>
    </div>

    <x-common.content-wrapper class="mt-5">
        <div class="tile is-ancestor">
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-green is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
                        <span class="icon mr-1">
                            <i class="fas fa-bank"></i>
                        </span>
                        <span>
                            Companies
                        </span>
                    </p>
                    <x-common.client-datatable
                        has-filter="false"
                        has-length-change="false"
                        paging-type="simple"
                        length-menu="[6]"
                    >
                        <x-slot name="headings">
                            <th><abbr> # </abbr></th>
                            <th><abbr> Company </abbr></th>
                            <th class="has-text-right"><abbr> Users </abbr></th>
                            <th class="has-text-right"><abbr> Branches </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($companies as $company)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $company->name }} </td>
                                    <td class="has-text-right"> {{ number_format($company->employees_count) }} </td>
                                    <td class="has-text-right"> {{ number_format($company->warehouses_count) }} </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-common.client-datatable>
                </div>
            </div>
            <div class="tile is-4 is-vertical is-parent">
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Companies Today</p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ $engagementReport->companies['activeCompaniesToday'] }}</p>
                        </div>
                        <div class="hero-foot pt-6 has-text-right">
                            <p class="text-green has-text-weight-bold">
                                <span class="icon is-size-6-5">
                                    <i class="far fa-circle-dot"></i>
                                </span>
                                <span>
                                    REALTIME
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Users Today</p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ $engagementReport->users['activeUsersToday'] }}</p>
                        </div>
                        <div class="hero-foot pt-6 has-text-right">
                            <p class="text-green has-text-weight-bold">
                                <span class="icon is-size-6-5">
                                    <i class="far fa-circle-dot"></i>
                                </span>
                                <span>
                                    REALTIME
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tile is-ancestor">
            <div class="tile is-4 is-vertical is-parent">
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Native Transactions Today </p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ number_format(collect($featureReport->transactionalFeatures)->sum('total_transactions')) }}</p>
                        </div>
                        <div class="hero-foot pt-6 has-text-right">
                            <p class="text-green has-text-weight-bold">
                                <span class="icon is-size-6-5">
                                    <i class="far fa-circle-dot"></i>
                                </span>
                                <span>
                                    REALTIME
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Pad Transactions Today </p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ number_format(collect($featureReport->padFeatures)->sum('total_transactions')) }}</p>
                        </div>
                        <div class="hero-foot pt-6 has-text-right">
                            <p class="text-green has-text-weight-bold">
                                <span class="icon is-size-6-5">
                                    <i class="far fa-circle-dot"></i>
                                </span>
                                <span>
                                    REALTIME
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-green is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
                        <span class="icon mr-1">
                            <i class="fas fa-cubes"></i>
                        </span>
                        <span>
                            Native Transactions
                        </span>
                    </p>
                    <x-common.client-datatable
                        has-filter="false"
                        has-length-change="false"
                        paging-type="simple"
                        length-menu="[5]"
                        x-bind:class="{ 'nowrap': false }"
                    >
                        <x-slot name="headings">
                            <th><abbr> # </abbr></th>
                            <th><abbr> Feature </abbr></th>
                            <th class="has-text-right"><abbr> Transactions </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($featureReport->transactionalFeatures as $transactionalFeature)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ str()->headline($transactionalFeature['feature']) }} </td>
                                    <td class="has-text-right"> {{ number_format($transactionalFeature['total_transactions']) }} </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-common.client-datatable>
                </div>
            </div>
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-green is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
                        <span class="icon mr-1">
                            <i class="fas fa-folder-open"></i>
                        </span>
                        <span>
                            Pad Transactions
                        </span>
                    </p>
                    <x-common.client-datatable
                        has-filter="false"
                        has-length-change="false"
                        paging-type="simple"
                        length-menu="[5]"
                        x-bind:class="{ 'nowrap': false }"
                    >
                        <x-slot name="headings">
                            <th><abbr> # </abbr></th>
                            <th><abbr> Pad </abbr></th>
                            <th class="has-text-right"><abbr> Transactions </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($featureReport->padFeatures as $padFeature)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ str()->headline($padFeature['feature']) }} </td>
                                    <td class="has-text-right"> {{ number_format($padFeature['total_transactions']) }} </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-common.client-datatable>
                </div>
            </div>
        </div>

        <div class="tile is-ancestor">
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-green is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
                        <span class="icon mr-1">
                            <i class="fas fa-file-contract"></i>
                        </span>
                        <span>
                            Subscriptions
                        </span>
                    </p>
                    <x-common.client-datatable
                        has-filter="false"
                        has-length-change="false"
                        paging-type="simple"
                        length-menu="[6]"
                    >
                        <x-slot name="headings">
                            <th><abbr> # </abbr></th>
                            <th><abbr> Company </abbr></th>
                            <th class="has-text-right"><abbr> Expiry Date </abbr></th>
                            <th class="has-text-right"><abbr> Days Left </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($subscriptionReport->getSubscriptionsThisAndNextMonth as $company)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $company->name }} </td>
                                    <td class="has-text-right"> {{ $company->subscription_expires_on->toFormattedDateString() }} </td>
                                    <td class="has-text-right"> {{ today()->diffInDays($company->subscription_expires_on, false) }} </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-common.client-datatable>
                </div>
            </div>
            <div class="tile is-4 is-vertical is-parent">
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Subscriptions This Month</p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ $subscriptionReport->getTotalSubscriptionsThisMonth }}</p>
                        </div>
                        <div class="hero-foot pt-6 has-text-right">
                            <p class="text-green has-text-weight-bold">
                                <span class="icon is-size-6-5">
                                    <i class="far fa-circle-dot"></i>
                                </span>
                                <span>
                                    REALTIME
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Subscriptions Next Month </p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ $subscriptionReport->getTotalSubscriptionsNextMonth }}</p>
                        </div>
                        <div class="hero-foot pt-6 has-text-right">
                            <p class="text-green has-text-weight-bold">
                                <span class="icon is-size-6-5">
                                    <i class="far fa-circle-dot"></i>
                                </span>
                                <span>
                                    REALTIME
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-common.content-wrapper>
@endsection

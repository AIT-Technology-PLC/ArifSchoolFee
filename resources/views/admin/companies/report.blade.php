@extends('layouts.app')

@section('title', $company->name)

@section('content')
    <div class="level mx-3 m-lr-0">
        <div class="level-left">
            <div class="level-item is-justify-content-left">
                <div class="heading text-green is-size-5 is-size-6-mobile">
                    Engagement Report
                </div>
            </div>
        </div>
        <div class="level-right m-top-0">
            <div class="level-item is-justify-content-left">
                <h1 class="heading text-green is-size-5 is-size-6-mobile has-text-weight-bold">
                    {{ $company->name }}
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
                            <i class="fas fa-users"></i>
                        </span>
                        <span>
                            Users
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
                            <th><abbr> Metric </abbr></th>
                            <th class="has-text-right"><abbr> Users </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($engagementReport->users as $key => $value)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ str()->headline($key) }} </td>
                                    <td class="has-text-right"> {{ number_format($value) }} </td>
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
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Branches Today</p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ $engagementReport->branches['activeBranchesToday'] }}</p>
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
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-green is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
                        <span class="icon mr-1">
                            <i class="fas fa-warehouse"></i>
                        </span>
                        <span>
                            Branches
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
                            <th><abbr> Metric </abbr></th>
                            <th class="has-text-right"><abbr> Branches </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($engagementReport->branches as $key => $value)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ str()->headline($key) }} </td>
                                    <td class="has-text-right"> {{ number_format($value) }} </td>
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
                            <i class="fas fa-building-user"></i>
                        </span>
                        <span>
                            Users Per Branches
                        </span>
                    </p>
                    <x-common.client-datatable
                        has-filter="false"
                        has-length-change="false"
                        paging-type="simple"
                        length-menu="[6]"
                        x-bind:class="{ 'nowrap': false }"
                    >
                        <x-slot name="headings">
                            <th><abbr> # </abbr></th>
                            <th><abbr> Branch </abbr></th>
                            <th class="has-text-right"><abbr> Users </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($engagementReport->getBranchesWithUserCount as $branch)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $branch->name }} </td>
                                    <td class="has-text-right"> {{ number_format($branch->original_users_count) }} </td>
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
                        length-menu="[6]"
                    >
                        <x-slot name="headings">
                            <th><abbr> # </abbr></th>
                            <th><abbr> Feature </abbr></th>
                            <th class="has-text-right"><abbr> Total Transactions</abbr></th>
                            <th class="has-text-right"><abbr> Incomplete Transactions</abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($transactionReport->transactionalFeatures as $transactionalFeature)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ str()->headline($transactionalFeature['feature']) }} </td>
                                    <td class="has-text-right"> {{ number_format($transactionalFeature['total_transactions']) }} </td>
                                    <td class="has-text-right"> {{ number_format($transactionalFeature['incomplete_transactions']) }} </td>
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
                        length-menu="[6]"
                        x-bind:class="{ 'nowrap': false }"
                    >
                        <x-slot name="headings">
                            <th><abbr> # </abbr></th>
                            <th><abbr> Pad </abbr></th>
                            <th class="has-text-right"><abbr> Total Transactions</abbr></th>
                            <th class="has-text-right"><abbr> Incomplete Transactions</abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($transactionReport->padFeatures as $padFeature)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ str()->headline($padFeature['feature']) }} </td>
                                    <td class="has-text-right"> {{ number_format($padFeature['total_transactions']) }} </td>
                                    <td class="has-text-right"> {{ number_format($padFeature['incomplete_transactions']) }} </td>
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
                            <i class="fas fa-sitemap"></i>
                        </span>
                        <span>
                            Master Data
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
                            <th><abbr> Feature </abbr></th>
                            <th class="has-text-right"><abbr> Total </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($transactionReport->masterFeatures as $masterFeature)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ str()->headline($masterFeature['feature']) }} </td>
                                    <td class="has-text-right"> {{ number_format($masterFeature['total']) }} </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-common.client-datatable>
                </div>
            </div>
        </div>
    </x-common.content-wrapper>
@endsection

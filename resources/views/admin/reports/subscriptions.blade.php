@extends('layouts.app')

@section('title', 'Subscription Report')

@section('content')
    <div class="level mx-3 m-lr-0">
        <div class="level-left">
            <div class="level-item is-justify-content-left">
                <div class="heading text-green is-size-5 is-size-6-mobile">
                    SUBSCRIPTION
                </div>
            </div>
        </div>
        <div class="level-right m-top-0">
            <div class="level-item is-justify-content-left">
                <h1 class="heading text-green is-size-5 is-size-6-mobile has-text-weight-bold">
                    REPORT
                </h1>
            </div>
        </div>
    </div>

    <x-common.report-filter action="{{ route('admin.reports.subscriptions') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    <div class="column is-12">
                        <x-forms.label>
                            Period
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.input
                                    type="text"
                                    id="period"
                                    name="subscription_period"
                                    class="is-size-7-mobile is-fullwidth has-text-centered"
                                    value="{{ request('subscription_period') ?? implode(' - ', [today(), today()->addMonths(3)]) }}"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </x-common.report-filter>

    <x-common.content-wrapper class="mt-5">
        <div class="tile is-ancestor">
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-green is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
                        <span class="icon mr-1">
                            <i class="fas fa-file-contract"></i>
                        </span>
                        <span>
                            Active Subscriptions
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
                            <p class="text-green is-uppercase heading is-size-6"> {{ today()->monthName }} Subscriptions </p>
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
                            <p class="text-green is-uppercase heading is-size-6"> {{ today()->addMonth()->monthName }} Subscriptions </p>
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

        <div class="tile is-ancestor">
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-green is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
                        <span class="icon mr-1">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                        <span>
                            Subscriptions By Months
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
                            <th><abbr> Month </abbr></th>
                            <th class="has-text-right"><abbr> Companies </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach (range(1, 12) as $month)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ now()->setMonth($month)->monthName }} </td>
                                    <td class="has-text-right"> {{ $subscriptionReport->getSubscriptionsCountByMonths[$month] ?? 0 }} </td>
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
                            <i class="fas fa-file-circle-xmark"></i>
                        </span>
                        <span>
                            Expired Subscriptions
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
                            <th><abbr> Company </abbr></th>
                            <th class="has-text-right"><abbr> Expiry Date </abbr></th>
                            <th class="has-text-right"><abbr> Days Left </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($subscriptionReport->getExpiredSubscriptions as $company)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $company->name }} </td>
                                    <td class="has-text-right"> {{ $company->subscription_expires_on->toFormattedDateString() }} </td>
                                    <td class="has-text-right">
                                        {{ today()->diffInDays($company->subscription_expires_on, false) }}
                                        ({{ $company->subscription_expires_on->diffForHumans(today()) }})
                                    </td>
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
                            <i class="fas fa-filter"></i>
                        </span>
                        <span>
                            Filtered Subscriptions
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
                            @foreach ($subscriptionReport->getFilteredSubscriptions as $company)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $company->name }} </td>
                                    <td class="has-text-right"> {{ $company->subscription_expires_on->toFormattedDateString() }} </td>
                                    <td class="has-text-right">
                                        {{ today()->diffInDays($company->subscription_expires_on, false) }}
                                    </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-common.client-datatable>
                </div>
            </div>
        </div>
    </x-common.content-wrapper>
@endsection

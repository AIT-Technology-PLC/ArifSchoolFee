@extends('layouts.app')

@section('title', 'Subscription Report')

@section('content')
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

    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Subscription Report"
                box-color="bg-softblue"
                amount="SR"
                icon="fas fa-heading"
            />
        </div> 
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="{{ today()->monthName }} Subscriptions"
                box-color="bg-lightblue"
                :amount="$subscriptionReport->getTotalSubscriptionsThisMonth"
                icon="fas fa-calendar-day"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="{{ today()->addMonth()->monthName }} Subscriptions"
                box-color="bg-red"
                :amount="$subscriptionReport->getTotalSubscriptionsNextMonth"
                icon="fas fa-calendar-day"
            />
        </div>
        <div class="column is-12-mobile is-12-tablet is-6-desktop">
            {!! $chart->container() !!}
        </div>
        <div class="column is-12-mobile is-12-tablet is-6-desktop">
            {!! $chartT->container() !!}
        </div>
    </div>

    <x-common.content-wrapper class="mt-5">
        <div class="tile is-ancestor">
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-softblue is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
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
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-softblue is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
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

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
    <script src="{{ $chartT->cdn() }}"></script>
    {{ $chartT->script() }}
@endsection

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="Companies Today"
                box-color="bg-blue"
                :amount="$engagementReport->companies['activeCompanies']"
                icon="fas fa-shop"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="Users Today"
                box-color="bg-brown"
                :amount="$engagementReport->users['activeUsersToday']"
                icon="fas fa-users"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="{{ today()->monthName }} Sub"
                box-color="bg-purple"
                :amount="$subscriptionReport->getTotalSubscriptionsThisMonth"
                icon="fas fa-clipboard-check"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="{{ today()->addMonth()->monthName }} Sub"
                box-color="bg-gold"
                :amount="$subscriptionReport->getTotalSubscriptionsThisMonth"
                icon="fas fa-clipboard-check"
            />
        </div>
        <div class="column is-full-mobile is-12-tablet is-half-desktop">
            {!! $chart->container() !!}
        </div>
        <div class="column is-full-mobile is-12-tablet is-half-desktop">
            {!! $chartT->container() !!}
        </div>
    </div>

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
    <script src="{{ $chartT->cdn() }}"></script>
    {{ $chartT->script() }}
@endsection

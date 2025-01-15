@extends('layouts.app')

@section('title', 'Main Menu')

@section('content')
    <div class="columns is-marginless is-multiline is-mobile">
        <div class="column is-6-mobile is-6-tablet is-4-desktop">
            <x-common.total-model
                model="Total Schools"
                headValue="Active Usage"
                box-color="bg-softblue"
                :amount="$dashboardReport->getTotalSchools"
                icon="fas fa-school"
            />
        </div>
        <div class="column is-6-mobile is-6-tablet is-4-desktop">
            <x-common.total-model
                model="Assigned Fee Count"
                headValue="This Month"
                box-color="bg-lightblue"
                :amount="$dashboardReport->getAssignedFeeMasterThisMonth"
                icon="fas fa-hand-holding-dollar"
            />
        </div>
        <div class="column is-12-mobile is-12-tablet is-4-desktop">
            <x-common.total-model
                model="Overdue Payment Count"
                headValue="This Month"
                box-color="bg-red"
                :amount="$dashboardReport->getOverduePaymentCount"
                icon="fas fa-calendar-times"
            />
        </div>
        <div class="column is-12-mobile is-12-tablet is-8-desktop">
            <div class="box  has-text-white is-borderless">
                {!! $chart->container() !!}
            </div>
        </div>
        <div class="column is-12-mobile is-12-tablet is-4-desktop">
            <div class="columns  is-multiline is-mobile">
                <div class="tile is-12 is-vertical is-parent">
                    <div class="tile is-child box" style="border-left: 2px solid">
                        <div class="hero">
                            <div class="hero-head">
                                <p class="text-softblue has-text-weight-bold is-uppercase heading is-size-6"> Completed Tranaction By User </p>
                            </div>
                            <div class="hero-body px-0 pt-1">
                                <p class="title text-softblue">{{ $dashboardReport->getCompletedTransactionByUserThisMonth }}</p>
                            </div>
                            <div class="hero-foot pt-6 has-text-right">
                                <p class="text-softblue has-text-weight-bold">
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
                    <div class="tile is-child box" style="border-left: 2px solid">
                        <div class="hero">
                            <div class="hero-head">
                                <p class="text-softblue has-text-weight-bold is-uppercase heading is-size-6">  Total Pending Transaction </p>
                            </div>
                            <div class="hero-body px-0 pt-1">
                                <p class="title text-softblue">{{ $dashboardReport->getPendingTransactionsThisMonth }}</p>
                            </div>
                            <div class="hero-foot pt-6 has-text-right">
                                <p class="text-softblue has-text-weight-bold">
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
        </div>
    </div>

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endsection
@extends('layouts.app')

@section('title', $school->name)

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Engagement Report"
                box-color="bg-softblue"
                amount="ER"
                icon="fas fa-heading"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="School Name"
                box-color="bg-green"
                :amount="$school->name"
                icon="fas fa-school"
            />
        </div>

        <div class="column is-12-mobile is-12-tablet is-6-desktop">
            {!! $chart->container() !!}
        </div>
        
        <div class="column is-12-mobile is-12-tablet is-6-desktop">
            {!! $chartT->container() !!}
        </div>

        <div class="column is-12-mobile is-12-tablet is-8-desktop">
            {!! $chartD->container() !!}
        </div>

        <div class="column is-12-mobile is-12-tablet is-4-desktop">
            <div class="columns is-multiline is-mobile">
                <div class="tile is-ancestor">
                    <div class="tile is-12 is-vertical is-parent">
                        <div class="tile is-child box" style="border-left: 2px solid">
                            <div class="hero">
                                <div class="hero-head">
                                    <p class="text-softblue has-text-weight-bold is-uppercase heading is-size-6"> Users Today</p>
                                </div>
                                <div class="hero-body px-0 pt-1">
                                    <p class="title text-softblue">{{ $engagementReport->users['activeUsersToday'] }}</p>
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
                                    <p class="text-softblue has-text-weight-bold is-uppercase heading is-size-6"> Branches Today</p>
                                </div>
                                <div class="hero-body px-0 pt-1">
                                    <p class="title text-softblue">{{ $engagementReport->branches['activeBranchesToday'] }}</p>
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
    </div>

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
    <script src="{{ $chartT->cdn() }}"></script>
    {{ $chartT->script() }}
    <script src="{{ $chartD->cdn() }}"></script>
    {{ $chartD->script() }}
@endsection

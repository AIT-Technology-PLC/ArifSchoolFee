@extends('layouts.app')

@section('title', 'User Report')

@section('content')
    <x-common.report-filter action="{{ route('admin.reports.users') }}">
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
                                    name="user_period"
                                    class="is-size-7-mobile is-fullwidth has-text-centered"
                                    value="{{ request('user_period') }}"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </x-common.report-filter>

    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="User Report"
                box-color="bg-softblue"
                amount="UR"
                icon="fas fa-heading"
            />
        </div> 
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Users"
                box-color="bg-lightblue"
                :amount="number_format($engagementReport->companies['companies']->sum('employees_count'))"
                icon="fas fa-user-group"
            />
        </div>
        <div class="column is-12-mobile is-12-tablet is-8-desktop">
            {!! $chart->container() !!}
        </div>
        <div class="column is-12-mobile is-12-tablet is-4-desktop">
            <div class="columns is-multiline is-mobile">
                <div class="tile is-ancestor">
                    <div class="tile is-parent">
                        <div class="tile is-12 is-vertical is-parent">
                            <div class="tile is-child box" style="border-left: 2px solid">
                                <div class="hero">
                                    <div class="hero-head">
                                        <p class="text-softblue has-text-weight-bold is-uppercase heading is-size-6"> Branches </p>
                                    </div>
                                    <div class="hero-body px-0 pt-1">
                                        <p class="title text-softblue">{{ number_format($engagementReport->companies['companies']->sum('warehouses_count')) }}</p>
                                    </div>
                                    <div class="hero-foot pt-6 has-text-right">
                                        <p class="text-softblue has-text-weight-bold">
                                            <span class="icon is-size-6-5">
                                                <i class="fas fa-filter"></i>
                                            </span>
                                            <span>
                                                FILTERED
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="tile is-child box" style="border-left: 2px solid">
                                <div class="hero">
                                    <div class="hero-head">
                                        <p class="text-softblue has-text-weight-bold is-uppercase heading is-size-6"> Companies </p>
                                    </div>
                                    <div class="hero-body px-0 pt-1">
                                        <p class="title text-softblue">{{ number_format($engagementReport->companies['activeCompanies']) }}</p>
                                    </div>
                                    <div class="hero-foot pt-6 has-text-right">
                                        <p class="text-softblue has-text-weight-bold">
                                            <span class="icon is-size-6-5">
                                                <i class="fas fa-filter"></i>
                                            </span>
                                            <span>
                                                FILTERED
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
    </div>

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endsection

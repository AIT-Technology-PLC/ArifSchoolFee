@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-common.content-wrapper>
        <div class="columns is-marginless is-multiline is-mobile">
            <div class="column is-6-mobile is-6-tablet is-3-desktop">
                <x-common.total-model
                    model="Total Student"
                    headValue="Student"
                    box-color="bg-softblue"
                    :amount="$dashboardReport->getTotalStudents"
                    icon="fas fa-user-graduate"
                />
            </div>
            <div class="column is-6-mobile is-6-tablet is-3-desktop">
                <x-common.total-model
                    model="Active Branch"
                    headValue="Branch"
                    box-color="bg-purple"
                    :amount="$dashboardReport->getActiveBranches"
                    icon="fas fa-code-branch"
                />
            </div>
            <div class="column is-6-mobile is-6-tablet is-3-desktop">
                <x-common.total-model
                    model="Total Staff"
                    headValue="Staff"
                    box-color="bg-red"
                    :amount="$dashboardReport->getTotalStaff"
                    icon="fas fa-user-group"
                />
            </div>
            <div class="column is-6-mobile is-6-tablet is-3-desktop">
                <x-common.total-model
                    model="This Month"
                    headValue="Estimated"
                    box-color="bg-softblue"
                    :amount="0"
                    icon="fas fa-coins"
                />
            </div>
            <div class="column is-12-mobile is-6-tablet is-8-desktop">
                {!! $chart->container() !!}
            </div>
            <div class="column is-12-mobile is-6-tablet is-4-desktop">
                <x-common.fee-data-shower-model
                    :estimated="$dashboardReport->getThisMonthEstimation"
                    :collected="$dashboardReport->getThisMonthCollectedAmount"
                />
            </div>
        </div>
    </x-common.content-wrapper>
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endsection
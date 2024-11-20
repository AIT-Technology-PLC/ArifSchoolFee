@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-common.content-wrapper>
        <div class="columns is-marginless is-multiline">
            <div class="column is-12-mobile is-6-tablet is-3-desktop p-lr-0">
                <x-common.total-model
                    model="Total Student"
                    headValue="Student"
                    box-color="bg-softblue"
                    :amount="$totalStudent"
                    icon="fas fa-user-graduate"
                />
            </div>
            <div class="column is-12-mobile is-6-tablet is-3-desktop p-lr-0">
                <x-common.total-model
                    model="Active Branch"
                    headValue="Branch"
                    box-color="bg-purple"
                    :amount="$activeBranches"
                    icon="fas fa-code-branch"
                />
            </div>
            <div class="column is-12-mobile is-6-tablet is-3-desktop p-lr-0">
                <x-common.total-model
                    model="Total Staff"
                    headValue="Staff"
                    box-color="bg-green"
                    :amount="$totalStaff"
                    icon="fas fa-user-group"
                />
            </div>
            <div class="column is-12-mobile is-6-tablet is-3-desktop p-lr-0">
                <x-common.total-model
                    model="This Month"
                    headValue="Revenue"
                    box-color="bg-softblue"
                    :amount="10"
                    icon="fas fa-coins"
                />
            </div>
        </div>
    </x-common.content-wrapper>
@endsection

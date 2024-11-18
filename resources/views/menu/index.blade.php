@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-common.content-wrapper>
        <div class="columns is-marginless is-multiline">
            <div class="column is-6-mobile is-6-tablet is-4-desktop p-lr-0">
                <x-common.total-model
                    model="Total Student"
                    headValue="Students"
                    box-color="bg-softblue"
                    :amount="12"
                    icon="fas fa-user-graduate"
                />
            </div>
            <div class="column is-6-mobile is-6-tablet is-4-desktop p-lr-0">
                <x-common.total-model
                    model="Total Staff"
                    headValue="Staffs"
                    box-color="bg-green"
                    :amount="$totalStaff"
                    icon="fas fa-user-group"
                />
            </div>
            <div class="column is-6-mobile is-6-tablet is-4-desktop p-lr-0">
                <x-common.total-model
                    model="This Month"
                    headValue="Revenue"
                    box-color="bg-lightpurple"
                    :amount="money(10)"
                    icon="fas fa-dollar"
                />
            </div>
        </div>
    </x-common.content-wrapper>
@endsection

@extends('layouts.app')

@section('title', 'User Report')

@section('content')
    <div class="level mx-3 m-lr-0">
        <div class="level-left">
            <div class="level-item is-justify-content-left">
                <div class="heading text-green is-size-5 is-size-6-mobile">
                    USER
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

    <x-common.content-wrapper class="mt-5">
        <div class="tile is-ancestor">
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Users </p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ number_format($engagementReport->companies['companies']->sum('employees_count')) }}</p>
                        </div>
                        <div class="hero-foot pt-6 has-text-right">
                            <p class="text-green has-text-weight-bold">
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
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Branches </p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ number_format($engagementReport->companies['companies']->sum('warehouses_count')) }}</p>
                        </div>
                        <div class="hero-foot pt-6 has-text-right">
                            <p class="text-green has-text-weight-bold">
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
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Companies </p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ number_format($engagementReport->companies['activeCompanies']) }}</p>
                        </div>
                        <div class="hero-foot pt-6 has-text-right">
                            <p class="text-green has-text-weight-bold">
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

        <div class="tile is-ancestor">
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-green is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
                        <span class="icon mr-1">
                            <i class="fas fa-bank"></i>
                        </span>
                        <span>
                            Companies
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
                            <th class="has-text-right"><abbr> Users </abbr></th>
                            <th class="has-text-right"><abbr> Branches </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($engagementReport->companies['companies'] as $company)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $company->name }} </td>
                                    <td class="has-text-right"> {{ number_format($company->employees_count) }} </td>
                                    <td class="has-text-right"> {{ number_format($company->warehouses_count) }} </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-common.client-datatable>
                </div>
            </div>
        </div>
    </x-common.content-wrapper>
@endsection

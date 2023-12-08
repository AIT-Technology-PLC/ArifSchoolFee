@extends('layouts.app')

@section('title', str($plan->name)->headline() . ' Plan')

@section('content')
    <div class="level mx-3 m-lr-0">
        <div class="level-left">
            <div class="level-item is-justify-content-left">
                <div class="heading text-green is-size-5 is-size-6-mobile">
                    {{ str($plan->name)->headline() }}
                    @if ($plan->isEnabled())
                        <span class="tag bg-lightgreen text-green has-text-weight-medium">
                            <span class="icon">
                                <i class="fas fa-dot-circle"></i>
                            </span>
                            <span>
                                Enabled
                            </span>
                        </span>
                    @else
                        <span class="tag bg-lightpurple text-purple has-text-weight-medium">
                            <span class="icon">
                                <i class="fas fa-warning"></i>
                            </span>
                            <span>
                                Disabled
                            </span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="level-right m-top-0">
            <div class="level-item is-justify-content-left">
                <h1 class="heading text-green is-size-5 is-size-6-mobile has-text-weight-bold">
                    PLAN
                </h1>
            </div>
        </div>
    </div>

    <x-common.content-wrapper class="mt-5">
        <div class="tile is-ancestor">
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Companies </p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ $plan->companies_count }}</p>
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
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <div class="hero">
                        <div class="hero-head">
                            <p class="text-green is-uppercase heading is-size-6"> Features </p>
                        </div>
                        <div class="hero-body px-0 pt-1">
                            <p class="title text-green">{{ $plan->features_count }}</p>
                        </div>
                        <div class="hero-foot pt-6 has-text-right">
                            <p class="text-green has-text-weight-bold">
                                <span class="icon is-size-6-5">
                                    <i class="fas fa-circle-dot"></i>
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
                            <th><abbr> Name </abbr></th>
                            <th><abbr> Status </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($plan->companies as $company)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $company->name }} </td>
                                    <td>
                                        @if ($company->isEnabled())
                                            <span class="tag bg-lightgreen text-green has-text-weight-medium">
                                                <span class="icon">
                                                    <i class="fas fa-dot-circle"></i>
                                                </span>
                                                <span>
                                                    Enabled
                                                </span>
                                            </span>
                                        @else
                                            <span class="tag bg-lightpurple text-purple has-text-weight-medium">
                                                <span class="icon">
                                                    <i class="fas fa-warning"></i>
                                                </span>
                                                <span>
                                                    Disabled
                                                </span>
                                            </span>
                                        @endif
                                    </td>
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
                            <i class="fas fa-cubes"></i>
                        </span>
                        <span>
                            Features
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
                            <th><abbr> Name </abbr></th>
                            <th><abbr> Status</abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($plan->features as $feature)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $feature->name }} </td>
                                    <td>
                                        @if ($feature->isEnabled())
                                            <span class="tag bg-lightgreen text-green has-text-weight-medium">
                                                <span class="icon">
                                                    <i class="fas fa-dot-circle"></i>
                                                </span>
                                                <span>
                                                    Enabled
                                                </span>
                                            </span>
                                        @else
                                            <span class="tag bg-lightpurple text-purple has-text-weight-medium">
                                                <span class="icon">
                                                    <i class="fas fa-warning"></i>
                                                </span>
                                                <span>
                                                    Disabled
                                                </span>
                                            </span>
                                        @endif
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

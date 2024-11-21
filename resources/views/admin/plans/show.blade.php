@extends('layouts.app')

@section('title', str($plan->name)->headline() . ' Plan')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Feature Name"
                box-color="bg-softblue"
                :amount="str($plan->name)->headline()"
                icon="fas fa-heading"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Status"
                box-color="bg-purple"
                :amount="$plan->isEnabled() ? 'Enabled' : 'Disabled'"
                icon="fas fa-check"
            />
        </div>

        <div class="column is-12-mobile is-12-tablet is-8-desktop">
            {!! $chart->container() !!}
        </div>

        <div class="column is-12-mobile is-12-tablet is-4-desktop">
            <div class="columns is-multiline is-mobile">
                <div class="tile is-ancestor">
                    <div class="tile is-12 is-vertical is-parent">
                        <div class="tile is-child box" style="border-left: 2px solid">
                            <div class="hero">
                                <div class="hero-head">
                                    <p class="text-softblue has-text-weight-bold is-uppercase heading is-size-6"> Companies </p>
                                </div>
                                <div class="hero-body px-0 pt-1">
                                    <p class="title text-softblue">{{ $plan->companies_count }}</p>
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
                                    <p class="text-softblue has-text-weight-bold is-uppercase heading is-size-6"> Features </p>
                                </div>
                                <div class="hero-body px-0 pt-1">
                                    <p class="title text-softblue">{{ $plan->features_count }}</p>
                                </div>
                                <div class="hero-foot pt-6 has-text-right">
                                    <p class="text-softblue has-text-weight-bold">
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
            </div>
        </div>
    </div>

    <x-common.content-wrapper class="mt-5">
        <div class="tile is-ancestor">
            <div class="tile is-parent">
                <div class="tile is-child box">
                    <p class="text-softblue is-uppercase heading is-size-5 mb-5 has-text-weight-bold">
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

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endsection

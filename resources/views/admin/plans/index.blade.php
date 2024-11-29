@extends('layouts.app')

@section('title', 'Subscription Plan')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Subscription Plan
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-tag" />
                        <span>
                            {{ number_format($plans->count()) }} {{ str()->plural('plan', $plans->count()) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            <x-common.button
                tag="a"
                href="{{ route('admin.plans.create') }}"
                mode="button"
                icon="fas fa-plus-circle"
                label="Create Plan"
                class="btn-softblue is-outlined is-small"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.client-datatable length-menu="[10]">
                <x-slot name="headings">
                    <th><abbr> # </abbr></th>
                    <th><abbr> Name </abbr></th>
                    <th><abbr> Features </abbr></th>
                    <th><abbr> Schools </abbr></th>
                    <th><abbr> Status </abbr></th>
                    <th><abbr> Action </abbr></th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($plans as $plan)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td> {{ str($plan->name)->headline() }} </td>
                            <td> {{ $plan->features_count }} </td>
                            <td> {{ $plan->companies_count }} </td>
                            <td>
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
                            </td>
                            <td>
                                <x-common.action-buttons
                                    :buttons="['edit','details']"
                                    model="admin.plans"
                                    :id="$plan->id"
                                />
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.client-datatable>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

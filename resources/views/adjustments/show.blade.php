@extends('layouts.app')

@section('title')
    Adjustment Details
@endsection

@section('content')
    <x-common.content-wrapper>

        <x-content.header title="General Information" />

        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-file-invoice"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $adjustment->code ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Adjustment No
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $adjustment->issued_on->toFormattedDateString() ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Issued On
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div>
                        <div class="columns is-marginless is-vcentered text-green">
                            <div class="column">
                                <div class="has-text-weight-bold">
                                    Details
                                </div>
                                <div class="is-size-7 mt-3">
                                    {!! is_null($adjustment->description) ? 'N/A' : nl2br(e($adjustment->description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">

        <x-content.header title="Adjustment Details">
            <a href="{{ route('adjustments.edit', $adjustment->id) }}" class="button is-small bg-green has-text-white">
                <span class="icon">
                    <i class="fas fa-pen"></i>
                </span>
                <span>
                    Edit
                </span>
            </a>
        </x-content.header>

        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />

            <x-common.success-message :message="session('successMessage')" />

            @if (!$adjustment->isApproved())
                @can('Approve Adjustment')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            This Adjustment has not been approved.
                            <br>
                            Click on the button below to approve this Adjustment.
                        </p>
                        <form id="formOne" action="{{ route('adjustments.approve', $adjustment->id) }}" method="post" novalidate>
                            @csrf
                            <button data-type="Adjustment" data-action="approve" data-description="" class="swal button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-signature"></i>
                                </span>
                                <span>
                                    Approve Adjustment
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <x-common.fail-message message="This Adjustment has not been approved yet." />
                @endcan
            @elseif (!$adjustment->isAdjusted())
                @can('Make Adjustment')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            Product(s) listed below are still not adjusted.
                            <br>
                            Click on the button below to adjust product(s) in inventory.
                        </p>
                        <form id="formOne" action="{{ route('adjustments.adjust', $adjustment->id) }}" method="post" novalidate>
                            @csrf
                            <button data-type="Adjustment" data-action="execute" data-description="" class="swal button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-eraser"></i>
                                </span>
                                <span>
                                    Execute Adjustment
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <x-common.fail-message message="Product(s) listed below are still not adjusted." />
                @endcan
            @else
                <x-common.success-message message="Products have been adjusted accordingly." />
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Operation </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Reason </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($adjustment->adjustmentDetails as $adjustmentDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $adjustmentDetail->is_subtract ? 'Subtract From ' : 'Add To ' }}
                                    {{ $adjustmentDetail->warehouse->name }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $adjustmentDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($adjustmentDetail->quantity, 2) }}
                                    {{ $adjustmentDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {!! nl2br(e($adjustmentDetail->reason)) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-content.footer>

    </x-common.content-wrapper>
@endsection

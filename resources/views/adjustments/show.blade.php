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
                    <x-common.show-data-section icon="fas fa-file-invoice"
                                                :data="$adjustment->code"
                                                label="Adjustment No" />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section icon="fas fa-calendar-day"
                                                :data="$adjustment->issued_on->toFormattedDateString()"
                                                label="Issued On" />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section type="long"
                                                :data="$adjustment->description"
                                                label="Details" />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Adjustment Details">
            @if (!$adjustment->isApproved() &&
        auth()->user()->can('Approve Adjustment'))
                <form id="formOne"
                      class="is-inline"
                      action="{{ route('adjustments.approve', $adjustment->id) }}"
                      method="post"
                      novalidate>
                    @csrf
                    <button data-type="Adjustment"
                            data-action="approve"
                            data-description=""
                            class="swal button btn-purple is-outlined is-small">
                        <span class="icon">
                            <i class="fas fa-signature"></i>
                        </span>
                        <span>
                            Approve
                        </span>
                    </button>
                </form>
            @elseif(!$adjustment->isAdjusted() && auth()->user()->can('Make Adjustment'))
                <form id="formOne"
                      class="is-inline"
                      action="{{ route('adjustments.adjust', $adjustment->id) }}"
                      method="post"
                      novalidate>
                    @csrf
                    <button data-type="Adjustment"
                            data-action="execute"
                            data-description=""
                            class="swal button btn-purple is-outlined is-small">
                        <span class="icon">
                            <i class="fas fa-eraser"></i>
                        </span>
                        <span>
                            Execute Adjustment
                        </span>
                    </button>
                </form>
            @endif
            <a href="{{ route('adjustments.edit', $adjustment->id) }}"
               class="button is-small bg-green has-text-white">
                <x-common.icon name="fas fa-pen" />
                <span> Edit </span>
            </a>
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if (!$adjustment->isApproved() &&
        !auth()->user()->can('Approve Adjustment'))
                <x-common.fail-message message="This Adjustment has not been approved yet." />
            @elseif (!$adjustment->isAdjusted() && !auth()->user()->can('Make Adjustment'))
                <x-common.fail-message message="Product(s) listed below are still not adjusted." />
            @elseif ($adjustment->isAdjusted())
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

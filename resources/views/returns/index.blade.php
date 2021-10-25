@extends('layouts.app')

@section('title')
    Return Management
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-arrow-alt-circle-left"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalReturns }}
                        </div>
                        <div class="is-size-7">
                            TOTAL RETURNS
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-6 p-lr-0">
            <div class="box text-purple">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column is-paddingless has-text-centered">
                        <div class="is-uppercase is-size-7">
                            Create new Return for products returned by customers
                        </div>
                        <div class="is-size-3">
                            <a
                                href="{{ route('returns.create') }}"
                                class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3"
                            >
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Return
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div
                class="box text-green has-text-centered"
                style="border-left: 2px solid #3d8660;"
            >
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalAdded }}
                </div>
                <div class="is-uppercase is-size-7">
                    Added
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div
                class="box text-gold has-text-centered"
                style="border-left: 2px solid #86843d;"
            >
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalNotAdded }}
                </div>
                <div class="is-uppercase is-size-7">
                    Approved (not Added)
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div
                class="box text-purple has-text-centered"
                style="border-left: 2px solid #863d63;"
            >
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalNotApproved }}
                </div>
                <div class="is-uppercase is-size-7">
                    Waiting Approval
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Return Management
            </h1>
        </div>
        <div class="box radius-top-0">
            <x-common.success-message :message="session('deleted')" />
            <div>
                <table
                    class="regular-datatable is-hoverable is-size-7 display nowrap"
                    data-date="[6]"
                    data-numeric="[]"
                >
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Return No </abbr></th>
                            <th><abbr> Status </abbr></th>
                            <th class="has-text-right"><abbr> Total Credit </abbr></th>
                            <th><abbr> Customer </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Issued On </abbr></th>
                            <th><abbr> Prepared By </abbr></th>
                            <th><abbr> Approved By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($returns as $return)
                            <tr
                                class="showRowDetails is-clickable"
                                data-id="{{ route('returns.show', $return->id) }}"
                            >
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized has-text-centered">
                                    {{ $return->code }}
                                </td>
                                <td class="is-capitalized">
                                    @if (!$return->isApproved())
                                        <span class="tag is-small bg-purple has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <span>
                                                Waiting Approval
                                            </span>
                                        </span>
                                    @elseif ($return->isAdded())
                                        <span class="tag is-small bg-green has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            <span>
                                                Added
                                            </span>
                                        </span>
                                    @else
                                        <span class="tag is-small bg-gold has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </span>
                                            <span>
                                                Approved (not Added)
                                            </span>
                                        </span>
                                    @endif
                                </td>
                                <td class="has-text-right">
                                    {{ userCompany()->currency }}.
                                    {{ number_format($return->grandTotalPrice, 2) }}
                                </td>
                                <td>
                                    {{ $return->customer->company_name ?? 'N/A' }}
                                </td>
                                <td class="description">
                                    {!! nl2br(e(substr($return->description, 0, 40))) ?? 'N/A' !!}
                                    <span class="is-hidden">
                                        {!! $return->description ?? '' !!}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    {{ $return->issued_on->toFormattedDateString() }}
                                </td>
                                <td> {{ $return->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $return->approvedBy->name ?? 'N/A' }} </td>
                                <td> {{ $return->updatedBy->name ?? 'N/A' }} </td>
                                <td class="actions">
                                    <a
                                        href="{{ route('returns.show', $return->id) }}"
                                        data-title="View Details"
                                    >
                                        <span class="tag is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a
                                        href="{{ route('returns.edit', $return->id) }}"
                                        data-title="Modify Return Data"
                                    >
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    <x-common.delete-button
                                        route="returns.destroy"
                                        :id="$return->id"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

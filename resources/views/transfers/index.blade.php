@extends('layouts.app')

@section('title')
    Transfer Management
@endsection

@section('content')
    <div class="columns is-marginless is-multiline is-centered">
        <div class="column is-6 p-lr-0">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-exchange-alt"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalTransfers }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Transfers
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
                            Create new Transfers for moving products among warehouses
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('transfers.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Transfers
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-green has-text-centered" style="border-left: 2px solid #3d8660;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalTransferred }}
                </div>
                <div class="is-uppercase is-size-7">
                    Transferred
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-blue has-text-centered" style="border-left: 2px solid #3d6386;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalSubtracted }}
                </div>
                <div class="is-uppercase is-size-7">
                    Subtracted (not Added)
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-gold has-text-centered" style="border-left: 2px solid #86843d;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalApproved }}
                </div>
                <div class="is-uppercase is-size-7">
                    Approved
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-purple has-text-centered" style="border-left: 2px solid #863d63;">
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
                Transfer Management
            </h1>
        </div>
        <div class="box radius-top-0">
            <x-success-message :message="session('deleted')" />
            <div>
                <table class="regular-datatable is-hoverable is-size-7 display nowrap" data-date="[4]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="has-text-centered"><abbr> Transfer No </abbr></th>
                            <th><abbr> Status </abbr></th>
                            <th><abbr> From </abbr></th>
                            <th><abbr> To </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Issued On </abbr></th>
                            <th><abbr> Prepared By </abbr></th>
                            <th><abbr> Approved By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($transfers as $transfer)
                            <tr class="showRowDetails is-clickable" data-id="{{ route('transfers.show', $transfer->id) }}">
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized has-text-centered">
                                    {{ $transfer->code }}
                                </td>
                                <td class="is-capitalized">
                                    @if ($transfer->isAdded())
                                        <span class="tag is-small bg-green has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            <span>
                                                Transferred
                                            </span>
                                        </span>
                                    @elseif ($transfer->isSubtracted())
                                        <span class="tag is-small bg-green has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            <span>
                                                Subtracted (not Added)
                                            </span>
                                        </span>
                                    @elseif($transfer->isApproved())
                                        <span class="tag is-small bg-gold has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </span>
                                            <span>
                                                Approved (not Transferred)
                                            </span>
                                        </span>
                                    @else
                                        <span class="tag is-small bg-purple has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <span>
                                                Waiting Approval
                                            </span>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $transfer->transferredFrom->name }}</td>
                                <td>{{ $transfer->transferredTo->name }}</td>
                                <td>
                                    {!! nl2br(e(substr($transfer->description, 0, 40))) ?? 'N/A' !!}
                                    <span class="is-hidden">
                                        {!! $transfer->description ?? '' !!}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    {{ $transfer->issued_on->toFormattedDateString() }}
                                </td>
                                <td> {{ $transfer->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $transfer->approvedBy->name ?? 'N/A' }} </td>
                                <td> {{ $transfer->updatedBy->name ?? 'N/A' }} </td>
                                <td class="actions">
                                    <a href="{{ route('transfers.show', $transfer->id) }}" data-title="View Details">
                                        <span class="tag is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a href="{{ route('transfers.edit', $transfer->id) }}" data-title="Modify Transfers Data">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    <x-delete-button model="transfers" :id="$transfer->id" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

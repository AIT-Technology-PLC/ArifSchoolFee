@extends('layouts.app')

@section('title')
    Tender Management
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-file-invoice"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalTenders }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Tenders
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-6">
            <div class="box text-purple">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column is-paddingless has-text-centered">
                        <div class="is-uppercase is-size-7">
                            Create new tender, track statuses and checklist items
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('tenders.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Tender
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Tender Management
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Tenders'])
            <div>
                <table id="table_id" class="is-hoverable is-size-7 display nowrap" data-date="[12,13,14]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th id="firstTarget"><abbr> # </abbr></th>
                            <th><abbr> Tender No </abbr></th>
                            <th><abbr> Type </abbr></th>
                            <th class="text-blue"><abbr> Status </abbr></th>
                            <th class="text-purple"><abbr> Checklist Completion </abbr></th>
                            <th><abbr> Customer </abbr></th>
                            <th class="has-text-centered"><abbr> Participants </abbr></th>
                            <th><abbr> Bid Bond Type</abbr></th>
                            <th><abbr> Bid Bond Amount</abbr></th>
                            <th><abbr> Bid Bond Validity</abbr></th>
                            <th><abbr> Price Description </abbr></th>
                            <th><abbr> Payment Term </abbr></th>
                            <th class="has-text-centered"><abbr> Items </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Published On </abbr></th>
                            <th class="has-text-right text-purple"><abbr> Closing Date </abbr></th>
                            <th class="has-text-right"><abbr> Opening Date </abbr></th>
                            <th><abbr> Prepared By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($tenders as $tender)
                            <tr class="showRowDetails is-clickable" data-id="{{ route('tenders.show', $tender->id) }}">
                                <td> {{ $loop->index + 1 }} </td>
                                <td>
                                    {{ $tender->code ?? 'N/A' }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $tender->type }}
                                </td>
                                <td class="is-capitalized">
                                    <span class="tag is-small bg-blue has-text-white">
                                        {{ $tender->status }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    <span class="tag is-small bg-purple has-text-white">
                                        {{ $tender->getTenderChecklistsCompletionRate() }}%
                                    </span>
                                </td>
                                <td>
                                    {{ $tender->customer->company_name ?? 'N/A' }}
                                </td>
                                <td class="has-text-centered">
                                    {{ $tender->participants ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $tender->bid_bond_type ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $tender->bid_bond_amount ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $tender->bid_bond_validity ?? 'N/A' }}
                                </td>
                                <td>
                                    {!! nl2br(e($tender->price)) !!}
                                </td>
                                <td>
                                    {!! nl2br(e($tender->payment_term)) !!}
                                </td>
                                <td class="has-text-centered">
                                    {{ $tender->tender_details_count }}
                                </td>
                                <td class="description">
                                    {!! nl2br(e(substr($tender->description, 0, 40))) ?? 'N/A' !!}
                                </td>
                                <td class="has-text-right">
                                    {{ $tender->published_on->toFormattedDateString() }}
                                </td>
                                <td class="has-text-right">
                                    <span style="background-color: inherit" class="tag is-small {{ $tender->closing_date->isPast() ? '' : 'bg-purple has-text-white' }}">
                                        {{ $tender->closing_date->toDayDateTimeString() }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small" style="background-color: inherit">
                                        {{ $tender->opening_date->toDayDateTimeString() }}
                                    </span>
                                </td>
                                <td> {{ $tender->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $tender->updatedBy->name ?? 'N/A' }} </td>
                                <td>
                                    <a class="is-block" href="{{ route('tenders.show', $tender->id) }}" data-title="View Details">
                                        <span class="tag mb-3 is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a class="is-block" href="{{ route('tenders.edit', $tender->id) }}" data-title="Modify Tender Data">
                                        <span class="tag mb-3 is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    <span class="is-block">
                                        @include('components.delete_button', ['model' => 'tenders',
                                        'id' => $tender->id])
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

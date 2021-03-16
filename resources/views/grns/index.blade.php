@extends('layouts.app')

@section('title')
    GRN Management
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
                            {{ $totalGrns }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total GRNs
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
                            Create new GRN for delivery and moving products out
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('grns.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New GRN
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
                GRN Management
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'GRN'])
            <div>
                <table id="table_id" class="is-hoverable is-size-7 display" data-date="[7]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th id="firstTarget"><abbr> # </abbr></th>
                            <th class="text-green"><abbr> Purchase No </abbr></th>
                            <th class="text-gold"><abbr> GRN No </abbr></th>
                            <th class="text-purple"><abbr> Status </abbr></th>
                            <th class="has-text-centered"><abbr> Items </abbr></th>
                            <th><abbr> Supplier </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Issued On </abbr></th>
                            <th><abbr> Prepared By </abbr></th>
                            <th><abbr> Approved By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($grns as $grn)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span class="tag is-small bg-green has-text-white receipt">
                                        {{ is_null($grn->purchase) ? 'N/A' : $grn->purchase->purchase_no }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    <span class="tag is-small bg-gold has-text-white grn">
                                        {{ $grn->code }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    @if (!$grn->isGrnApproved())
                                        <span class="tag is-small has-background-grey-dark has-text-white">
                                            Waiting for Approval
                                        </span>
                                    @elseif ($grn->isAddedToInventory())
                                        <span class="tag is-small bg-purple has-text-white">
                                            {{ $grn->status ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="tag is-small bg-blue has-text-white">
                                            {{ $grn->status ?? 'N/A' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="has-text-centered">
                                    {{ $grn->grn_details_count ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $grn->supplier->company_name ?? 'N/A' }}
                                </td>
                                <td class="description">
                                    {!! nl2br(e(substr($grn->description, 0, 40))) ?? 'N/A' !!}
                                </td>
                                <td class="has-text-right">
                                    {{ $grn->issued_on->toFormattedDateString() }}
                                </td>
                                <td> {{ $grn->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $grn->approvedBy->name ?? 'N/A' }} </td>
                                <td> {{ $grn->updatedBy->name ?? 'N/A' }} </td>
                                <td>
                                    <a class="is-block" href="{{ route('grns.show', $grn->id) }}" data-title="View Details">
                                        <span class="tag mb-3 is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a class="is-block" href="{{ route('grns.edit', $grn->id) }}" data-title="Modify GRN Data">
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
                                        @include('components.delete_button', ['model' => 'grns',
                                        'id' => $grn->id])
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

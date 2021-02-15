@extends('layouts.app')

@section('title')
    SIV/GDN Management
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
                            {{ $totalGdns }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total SIVs/GDNs
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
                            Create new SIV/GDN for delivery and moving products out
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('gdns.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New SIV/GDN
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
                SIV/GDN Management
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'SIV/GDN'])
            <div class="table-container">
                <table id="table_id" class="is-hoverable is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="text-green"><abbr> Receipt No </abbr></th>
                            <th class="text-gold"><abbr> SIV/GDN No </abbr></th>
                            <th class="text-purple"><abbr> Status </abbr></th>
                            <th class="has-text-centered"><abbr> Products </abbr></th>
                            <th><abbr> Customer </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Issued On </abbr></th>
                            @can('delete', $gdns->first())
                                <th><abbr> Added By </abbr></th>
                                <th><abbr> Edited By </abbr></th>
                            @endcan
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($gdns as $gdn)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span class="tag is-small bg-green has-text-white receipt">
                                        {{ is_null($gdn->sale) ? 'N/A' : $gdn->sale->receipt_no }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    <span class="tag is-small bg-gold has-text-white gdn">
                                        {{ $gdn->code }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    @if ($gdn->isGdnSubtracted())
                                        <span class="tag is-small bg-purple has-text-white">
                                            {{ $gdn->status ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="tag is-small bg-blue has-text-white">
                                            {{ $gdn->status ?? 'N/A' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="has-text-centered">
                                    {{ $gdn->gdn_details_count ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $gdn->customer->company_name ?? 'N/A' }}
                                </td>
                                <td class="description">
                                    {{ substr($gdn->description, 0, 40) ?? 'N/A' }}
                                </td>
                                <td class="has-text-right">
                                    {{ $gdn->issued_on->toFormattedDateString() }}
                                </td>
                                @can('delete', $gdn)
                                    <td> {{ $gdn->createdBy->name ?? 'N/A' }} </td>
                                    <td> {{ $gdn->updatedBy->name ?? 'N/A' }} </td>
                                @endcan
                                <td>
                                    <a class="is-block" href="{{ route('gdns.show', $gdn->id) }}" data-title="View Details">
                                        <span class="tag mb-3 is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a class="is-block" href="{{ route('gdns.edit', $gdn->id) }}" data-title="Modify SIV/GDN Data">
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
                                        @include('components.delete_button', ['model' => 'gdns',
                                        'id' => $gdn->id])
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

@extends('layouts.app')

@section('title')
    Tender Checklist Types
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-tasks"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalTenderChecklistTypes }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Checklist Categories
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
                            Create new tender checklist categories
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('tender-checklist-types.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Checklist Category
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
                Tender Checklist Types List
            </h1>
        </div>
        <div class="box radius-top-0">
            <x-success-message :message="session('deleted')" />
            <div>
                <table class="regular-datatable is-hoverable is-size-7 display nowrap" data-date="[3]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="text-green"><abbr> Name </abbr></th>
                            <th><abbr> Confidential </abbr></th>
                            <th class="has-text-centered"><abbr> Checklists </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th><abbr> Added On </abbr></th>
                            <th><abbr> Added By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenderChecklistTypes as $tenderChecklistType)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span class="tag bg-green has-text-white">
                                        {{ $tenderChecklistType->name }}
                                    </span>
                                </td>
                                <td>
                                    @if ($tenderChecklistType->is_sensitive)
                                        <span class="tag btn-purple is-outlined has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <span> Yes </span>
                                        </span>
                                    @else
                                        <span class="tag btn-green is-outlined has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-lock-open"></i>
                                            </span>
                                            <span> No </span>
                                        </span>
                                    @endif
                                </td>
                                <td class="has-text-centered">
                                    {{ $tenderChecklistType->general_tender_checklists_count }}
                                </td>
                                <td>
                                    {{ $tenderChecklistType->description ?? 'N/A' }}
                                </td>
                                <td> {{ $tenderChecklistType->created_at->toDayDateTimeString() }} </td>
                                <td> {{ $tenderChecklistType->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $tenderChecklistType->updatedBy->name ?? 'N/A' }} </td>
                                <td>
                                    <a href="{{ route('tender-checklist-types.edit', $tenderChecklistType->id) }}" data-title="Modify Tender Checklist Type Data">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    <x-delete-button model="tender-checklist-types" :id="$tenderChecklistType->id" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

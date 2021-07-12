@extends('layouts.app')

@section('title')
    Tender Checklist Types
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Tender Checklist Types List
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Tender checklist type'])
            <div>
                <table class="is-hoverable is-size-7 display nowrap" data-date="[3]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="text-green"><abbr> Name </abbr></th>
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
                                    {{ $tenderChecklistType->description ?? 'N/A' }}
                                </td>
                                <td> {{ $tenderChecklistType->created_at->toDayDateTimeString() }} </td>
                                <td> {{ $tenderChecklistType->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $tenderChecklistType->updatedBy->name ?? 'N/A' }} </td>
                                <td>
                                    <a href="{{ route('general-tender-checklists.edit', $tenderChecklistType->id) }}" data-title="Modify Tender Checklist Type Data">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    <span>
                                        @include('components.delete_button', ['model' => 'general-tender-checklists',
                                        'id' => $tenderChecklistType->id])
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

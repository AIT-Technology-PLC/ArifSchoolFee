@extends('layouts.app')

@section('title')
    General Tender Checklists
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                General Tender Checklists List
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Tender checklist item'])
            <div>
                <table class="is-hoverable is-size-7 display nowrap" data-date="[3]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="text-green"><abbr> Item </abbr></th>
                            <th><abbr> Type </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th><abbr> Added On </abbr></th>
                            <th><abbr> Added By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($generalTenderChecklists as $generalTenderChecklist)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span class="tag bg-green has-text-white">
                                        {{ $generalTenderChecklist->item }}
                                    </span>
                                </td>
                                <td>
                                    {{ $generalTenderChecklist->tenderChecklistType->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $generalTenderChecklist->description ?? 'N/A' }}
                                </td>
                                <td> {{ $generalTenderChecklist->created_at->toDayDateTimeString() }} </td>
                                <td> {{ $generalTenderChecklist->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $generalTenderChecklist->updatedBy->name ?? 'N/A' }} </td>
                                <td>
                                    <a href="{{ route('general-tender-checklists.edit', $generalTenderChecklist->id) }}" data-title="Modify General Tender Checklist Data">
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
                                        'id' => $generalTenderChecklist->id])
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

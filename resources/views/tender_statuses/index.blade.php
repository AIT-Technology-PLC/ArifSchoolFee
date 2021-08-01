@extends('layouts.app')

@section('title')
    Tender Status
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Tender Status List
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Tender Status'])
            <div>
                <table class="regular-datatable is-hoverable is-size-7 display nowrap" data-date="[]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="text-green"><abbr> Status </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th><abbr> Added By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenderStatuses as $tenderStatus)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span class="tag bg-green has-text-white">
                                        {{ $tenderStatus->status }}
                                    </span>
                                </td>
                                <td>
                                    {{ $tenderStatus->description ?? 'N/A' }}
                                </td>
                                <td> {{ $tenderStatus->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $tenderStatus->updatedBy->name ?? 'N/A' }} </td>
                                <td>
                                    <a href="{{ route('tender-statuses.edit', $tenderStatus->id) }}" data-title="Modify Tender Status Data">
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
                                        @include('components.delete_button', ['model' => 'tender-statuses',
                                        'id' => $tenderStatus->id])
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

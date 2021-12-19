@extends('layouts.app')

@section('title', 'Tender Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information">
            @if (!$tender->financial_reading && !$tender->technical_reading)
                <x-common.button
                    tag="a"
                    :href="route('tender-readings.edit', $tender->id)"
                    mode="button"
                    icon="fas fa-table"
                    label="Create Readings"
                    class="btn-green is-outlined is-small"
                />
            @endif
            <x-common.button
                tag="a"
                :href="route('tenders.edit', $tender->id)"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="btn-green is-outlined is-small"
            />
        </x-content.header>
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-tasks"
                        :data="$tender->code ?? 'N/A'"
                        label="Tender No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-columns"
                        :data="$tender->type ?? 'N/A'"
                        label="Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-info"
                        :data="$tender->status ?? 'N/A'"
                        label="Status"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-users"
                        :data="$tender->participants ?? 'N/A'"
                        label="Participants"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-alt"
                        data="{{ $tender->bid_bond_type ?? 'N/A' }} | {{ $tender->bid_bond_amount ?? 'N/A' }} | {{ $tender->bid_bond_validity ?? 'N/A' }}"
                        label="Bid Bond Info"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$tender->customer->company_name ?? 'N/A'"
                        label="Customer"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-spinner"
                        data="{{ $tender->tenderChecklistsCompletionRate }}%"
                        label="Checklist Completion"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-bill-wave"
                        data="{{ $tender->price ? '' : 'N/A' }} {!! nl2br(e($tender->price)) !!}"
                        label="Price"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice-dollar"
                        data="{{ $tender->payment_term ? '' : 'N/A' }} {!! nl2br(e($tender->payment_term)) !!}"
                        label="Payment Term"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$tender->description"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Schedules" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$tender->published_on->toFormattedDateString() ?? 'N/A'"
                        label="Publishing Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$tender->closing_date->toDayDateTimeString() ?? 'N/A'"
                        label="Closing Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$tender->opening_date->toDayDateTimeString() ?? 'N/A'"
                        label="Opening Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$tender->clarify_on ? $tender->clarify_on->toFormattedDateString() : 'N/A'"
                        label="Clarification Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$tender->visit_on ? $tender->visit_on->toFormattedDateString() : 'N/A'"
                        label="Visiting Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$tender->premeet_on ? $tender->premeet_on->toFormattedDateString() : 'N/A'"
                        label="Pre-meeting Date"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Lots" />
        <x-content.footer>
            <x-common.success-message :message="session('lotDetailDeleted') || session('lotDeleted')" />
            @foreach ($tender->tenderLots as $tenderLot)
                <x-common.content-wrapper>
                    <x-content.header title="Lot #{{ $loop->iteration }}">
                        @if ($tender->tenderLots->count() > 1)
                            @can('Delete Tender')
                                <form
                                    x-data="swal('delete', 'delete this lot')"
                                    action="{{ route('tender-lots.destroy', $tenderLot->id) }}"
                                    method="post"
                                    novalidate
                                    @submit.prevent="open"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <x-common.button
                                        tag="button"
                                        mode="button"
                                        icon="fas fa-trash"
                                        label="Delete Lot"
                                        x-ref="submitButton"
                                        class="is-small btn-purple is-outlined has-text-weight-medium"
                                    />
                                </form>
                            @endcan
                        @endif
                    </x-content.header>
                    <x-content.footer>
                        <x-common.bulma-table>
                            <x-slot name="headings">
                                <th> # </th>
                                <th> Product </th>
                                <th> Quantity </th>
                                <th> Description </th>
                                <th> Actions </th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($tenderLot->tenderLotDetails as $tenderLotDetail)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td class="is-capitalized">
                                            <span>
                                                {{ $tenderLotDetail->product->name }}
                                            </span>
                                            <span class="has-text-grey {{ $tenderLotDetail->product->code ? '' : 'is-hidden' }}">
                                                ({{ $tenderLotDetail->product->code }})
                                            </span>
                                        </td>
                                        <td>
                                            {{ number_format($tenderLotDetail->quantity, 2) }}
                                            {{ $tenderLotDetail->product->unit_of_measurement }}
                                        </td>
                                        <td>
                                            {!! nl2br(e($tenderLotDetail->description)) !!}
                                        </td>
                                        <td>
                                            <x-common.action-buttons
                                                :buttons="['delete']"
                                                model="tender-lot-details"
                                                :id="$tenderLotDetail->id"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.bulma-table>
                    </x-content.footer>
                </x-common.content-wrapper>
            @endforeach
        </x-content.footer>
    </x-common.content-wrapper>

    @if ($tender->technical_reading || $tender->financial_reading)
        <x-common.content-wrapper class="mt-5">
            <x-content.header title="Readings">
                <x-common.button
                    tag="a"
                    :href="route('tender-readings.edit', $tender->id)"
                    mode="button"
                    icon="fas fa-table"
                    label="Edit Readings"
                    class="btn-green is-outlined is-small"
                />
            </x-content.header>
            <x-content.footer>
                @if ($tender->financial_reading)
                    <div>
                        @can('Read Tender Sensitive Data')
                            <h1 class="is-size-5 text-green is-uppercase has-text-weight-medium has-text-centered"> Financial Reading </h1>
                            <div class="summernote-table">
                                {!! $tender->financial_reading ?? '' !!}
                            </div>
                            <hr class="mt-0 mx-6">
                        @else
                            <x-common.fail-message message="You don't have the permission to view finanical reading information." />
                        @endcan
                    </div>
                @endif
                @if ($tender->technical_reading)
                    <div>
                        <h1 class="is-size-5 text-green is-uppercase has-text-weight-medium has-text-centered"> Technical Reading </h1>
                        <div class="summernote-table">
                            {{ $tender->technical_reading ?? '' }}
                        </div>
                    </div>
                @endif
            </x-content.footer>
        </x-common.content-wrapper>
    @endif

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Checklists">
            <x-common.button
                tag="a"
                :href="route('tenders.print', $tender->id)"
                target="_blank"
                mode="button"
                icon="fas fa-print"
                label="Print"
                class="btn-green is-outlined is-small is-hidden-mobile"
            />
            <x-common.button
                tag="a"
                :href="route('tender-checklists-assignments.edit', $tender->id)"
                mode="button"
                icon="fas fa-user-check"
                label="Checklist Assignments"
                class="btn-green is-outlined is-small"
            />
            <x-common.button
                tag="a"
                :href="route('tenders.tender-checklists.create', $tender->id)"
                mode="button"
                icon="fas fa-plus-circle"
                label="Add New Checklist"
                class="btn-green is-outlined is-small"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.bulma-table>
                <x-slot name="headings">
                    <th><abbr> # </abbr></th>
                    <th><abbr> Item </abbr></th>
                    <th><abbr> Assignee </abbr></th>
                    <th><abbr> Status </abbr></th>
                    <th><abbr> Comment </abbr></th>
                    <th><abbr> Actions </abbr></th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($tender->tenderChecklists as $tenderChecklist)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized">
                                {{ $tenderChecklist->generalTenderChecklist->item }}
                            </td>
                            <td class="is-capitalized">
                                {{ $tenderChecklist->assignedTo->name ?? 'N/A' }}
                            </td>
                            <td class="is-capitalized">
                                {{ $tenderChecklist->status ?? 'N/A' }}
                            </td>
                            <td>
                                @if (!$tenderChecklist->generalTenderChecklist->tenderChecklistType->isSensitive())
                                    {!! $tenderChecklist->comment ? nl2br(e($tenderChecklist->comment)) : 'N/A' !!}
                                @else
                                    @can('Read Tender Sensitive Data')
                                        {!! $tenderChecklist->comment ? nl2br(e($tenderChecklist->comment)) : 'N/A' !!}
                                    @else
                                        <span class="tag text-purple">
                                            <span class="icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </span>
                                            <span>
                                                Permission Required
                                            </span>
                                        </span>
                                    @endcan
                                @endif
                            </td>
                            <td>
                                <x-common.action-buttons
                                    :buttons="['edit', 'delete']"
                                    model="tender-checklists"
                                    :id="$tenderChecklist->id"
                                />
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.bulma-table>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

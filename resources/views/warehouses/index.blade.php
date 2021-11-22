@extends('layouts.app')

@section('title', 'Warehouses')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Warehouses"
                :amount="$totalWarehouses"
                icon="fas fa-warehouse"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalActiveWarehouses"
                border-color="#3d8660"
                text-color="text-green"
                label="Active"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalInActiveWarehouses"
                border-color="#863d63"
                text-color="text-purple"
                label="Inactive"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Warehouses">
            @can('Create Warehouse')
                <x-common.button
                    tag="a"
                    href="{{ route('warehouses.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Warehouse"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.client-datatable date-columns="[9]">
                <x-slot name="headings">
                    <th><abbr> # </abbr></th>
                    <th><abbr> Warehouse Name </abbr></th>
                    <th><abbr> Location </abbr></th>
                    <th><abbr> Status </abbr></th>
                    <th><abbr> Type </abbr></th>
                    <th><abbr> Can Be Sold From </abbr></th>
                    <th><abbr> Email </abbr></th>
                    <th><abbr> Phone </abbr></th>
                    <th><abbr> Description </abbr></th>
                    <th class="has-text-right"><abbr> Created On </abbr></th>
                    <th><abbr> Added By </abbr></th>
                    <th><abbr> Edited By </abbr></th>
                    <th><abbr> Actions </abbr></th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($warehouses as $warehouse)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized"> {{ $warehouse->name }} </td>
                            <td class="is-capitalized">{{ $warehouse->location ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if ($warehouse->isActive())
                                    <span class="icon is-small text-green">
                                        <i class="fas fa-circle"></i>
                                    </span>
                                    <span class="text-green"> Active </span>
                                @else
                                    <span class="icon is-small text-purple">
                                        <i class="fas fa-circle"></i>
                                    </span>
                                    <span class="text-purple"> Not Active </span>
                                @endif
                            </td>
                            <td>
                                @if ($warehouse->is_sales_store)
                                    Sales Store
                                @else
                                    Main Store
                                @endif
                            </td>
                            <td>
                                @if ($warehouse->can_be_sold_from)
                                    <span class="tag btn-green is-outlined has-text-white">
                                        <span class="icon">
                                            <i class="fas fa-lock-open"></i>
                                        </span>
                                        <span> Yes </span>
                                    </span>
                                @else
                                    <span class="tag btn-purple is-outlined has-text-white">
                                        <span class="icon">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <span> No </span>
                                    </span>
                                @endif
                            </td>
                            <td>{{ $warehouse->email ?? 'N/A' }}</td>
                            <td>{{ $warehouse->phone ?? 'N/A' }}</td>
                            <td> {!! nl2br(e(substr($warehouse->description, 0, 40))) ?? 'N/A' !!} </td>
                            <td class="has-text-right"> {{ $warehouse->created_at->toFormattedDateString() }} </td>
                            <td> {{ $warehouse->createdBy->name ?? 'N/A' }} </td>
                            <td> {{ $warehouse->updatedBy->name ?? 'N/A' }} </td>
                            <td>
                                <a
                                    href="{{ route('warehouses.edit', $warehouse->id) }}"
                                    data-title="Modify Warehouse Data"
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
                                    route="warehouses.destroy"
                                    :id="$warehouse->id"
                                />
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.client-datatable>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@extends('layouts.app')

@section('title', 'Inventory Level Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter action="{{ route('reports.inventory_level') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    <div class="column is-12">
                        <x-forms.label>
                            Date
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.input
                                    class="is-size-7-mobile is-fullwidth has-text-centered"
                                    type="date"
                                    name="date"
                                    id="date"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('date') ?? now()->toDateString() }}"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </x-common.report-filter>

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-12 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-warehouse"></i>
                        </span>
                        <span>Inventory Level</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="true"
                    has-length-change="true"
                    paging-type="simple"
                >
                    <x-slot name="headings">
                        <th>#</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Category</th>
                        @foreach (authUser()->getAllowedWarehouses('read') as $warehouse)
                            <th>{{ ucfirst($warehouse->name) }}</th>
                        @endforeach
                        <th>Total Balance</th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($inventoryLevelReport->getInventoryLevels as $inventoryLevel)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $inventoryLevel['product'] }} </td>
                                <td> {{ $inventoryLevel['type'] }} </td>
                                <td> {{ $inventoryLevel['category'] }} </td>
                                @foreach (authUser()->getAllowedWarehouses('read') as $warehouse)
                                    <th>
                                        <span class='tag is-small @if ($inventoryLevel[$warehouse->name] ?? 0 > $inventoryLevel['min_on_hand']) btn-green is-outlined @elseif($inventoryLevel[$warehouse->name] ?? 0 == 0) btn-purple is-outlined @else btn bg-gold has-text-white @endif'>
                                            {{ $inventoryLevel[$warehouse->name] ?? 0.0 }} {{ $inventoryLevel['unit'] }}
                                        </span>
                                    </th>
                                @endforeach
                                <td> {{ view('components.datatables.green-solid-tag', [
                                    'amount' => $inventoryLevel['total_balance'],
                                    'unit' => $inventoryLevel['unit'],
                                ]) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection

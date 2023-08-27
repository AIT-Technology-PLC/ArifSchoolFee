@extends('layouts.app')

@section('title', 'Inventory Summary Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter action="{{ route('reports.inventory_summary') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    <div class="column is-12">
                        <x-forms.label>
                            Period
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.input
                                    type="text"
                                    id="period"
                                    name="period"
                                    class="is-size-7-mobile is-fullwidth has-text-centered"
                                    value="{{ request('period') }}"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Branch
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="branches"
                                    name="branches"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Branches </option>
                                    <option
                                        value=""
                                        @selected(request('branches') == '')
                                    > All </option>
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            @selected(request('branches') == $warehouse->id)
                                        > {{ $warehouse->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Product
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="product_id"
                                    name="product_id"
                                    class="is-size-7-mobile is-fullwidth"
                                    x-init="initializeSelect2($el)"
                                >
                                    <option
                                        value=" "
                                        @selected(request('product_id') == '')
                                    > All </option>
                                    @foreach ($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            @selected(request('product_id') == $product->id)
                                        >{{ $product->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-large is-left"
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
                            <i class="fas fa-chart-simple"></i>
                        </span>
                        <span>Inventory Movement Summary</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu="[5, 10, 15, 20]"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Branch </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th class="has-text-right"><abbr> Incoming </abbr></th>
                        <th class="has-text-right"><abbr> Outgoing </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($inventorySummaryReport->getGeneralSummaries as $generalSummary)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $generalSummary->branch_name }} </td>
                                <td> {{ $generalSummary->product_name }} </td>
                                @include('components.datatables.item-quantity', [
                                    'item' => [
                                        'function' => 'add',
                                        'quantity' => number_format($generalSummary->incoming, 2),
                                        'unit_of_measurement' => $generalSummary->unit_of_measurement,
                                    ],
                                ])
                                @include('components.datatables.item-quantity', [
                                    'item' => [
                                        'function' => 'subtract',
                                        'quantity' => number_format($generalSummary->outgoing, 2),
                                        'unit_of_measurement' => $generalSummary->unit_of_measurement,
                                    ],
                                ])
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
        @if (isFeatureEnabled('Damage Management'))
            @can('Read Damage')
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-bolt"></i>
                                </span>
                                <span>Damages Report</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Branch </abbr></th>
                                <th><abbr> Product </abbr></th>
                                <th class="has-text-right"><abbr> Quantity </abbr></th>
                                <th><abbr> Date </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($inventorySummaryReport->getDamageReports as $damageReport)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $damageReport->branch_name }} </td>
                                        <td> {{ $damageReport->product_name }} </td>
                                        <td class="has-text-right"> {{ number_format($damageReport->quantity, 2) . ' ' . $damageReport->unit_of_measurement }} </td>
                                        <td> {{ $damageReport->issued_on->toFormattedDateString() }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endcan
        @endif

        @if (isFeatureEnabled('Grn Management'))
            @can('Read GRN')
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-file-import"></i>
                                </span>
                                <span>Goods Recevied Notes Report</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Branch </abbr></th>
                                <th><abbr> Product </abbr></th>
                                <th class="has-text-right"><abbr> Quantity </abbr></th>
                                <th><abbr> Date </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($inventorySummaryReport->getGrnReports as $grnReport)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $grnReport->branch_name }} </td>
                                        <td> {{ $grnReport->product_name }} </td>
                                        <td class="has-text-right"> {{ number_format($grnReport->quantity, 2) . ' ' . $grnReport->unit_of_measurement }} </td>
                                        <td> {{ $grnReport->issued_on->toFormattedDateString() }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endcan
        @endif

        @if (isFeatureEnabled('Inventory Adjustment'))
            @can('Read Adjustment')
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-eraser"></i>
                                </span>
                                <span>Adjustments Report</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Branch </abbr></th>
                                <th><abbr> Product </abbr></th>
                                <th class="has-text-right"><abbr> Quantity </abbr></th>
                                <th><abbr> Date </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($inventorySummaryReport->getAdjustmentReports as $adjustmentReport)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $adjustmentReport->branch_name }} </td>
                                        <td> {{ $adjustmentReport->product_name }} </td>
                                        <td class="has-text-right"> {{ number_format($adjustmentReport->quantity, 2) . ' ' . $adjustmentReport->unit_of_measurement }} </td>
                                        <td> {{ $adjustmentReport->issued_on->toFormattedDateString() }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endcan
        @endif

        @if (isFeatureEnabled('Job Management'))
            @can('Read Job')
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-industry"></i>
                                </span>
                                <span>Production Report</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Branch </abbr></th>
                                <th><abbr> Product </abbr></th>
                                <th class="has-text-right"><abbr> Quantity </abbr></th>
                                <th><abbr> Date </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($inventorySummaryReport->getProductionReports as $productionReport)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $productionReport->branch_name }} </td>
                                        <td> {{ $productionReport->product_name }} </td>
                                        <td class="has-text-right"> {{ number_format($productionReport->quantity, 2) . ' ' . $productionReport->unit_of_measurement }} </td>
                                        <td> {{ $productionReport->issued_on->toFormattedDateString() }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endcan
        @endif

        @if (isFeatureEnabled('Transfer Management'))
            @can('Read Transfer')
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-exchange-alt"></i>
                                </span>
                                <span>Transfers Report</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Branch </abbr></th>
                                <th><abbr> Product </abbr></th>
                                <th><abbr> Operation </abbr></th>
                                <th class="has-text-right"><abbr> Quantity </abbr></th>
                                <th><abbr> Date </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($inventorySummaryReport->getTransferReports as $transferReport)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $transferReport->branch_name }} </td>
                                        <td> {{ $transferReport->product_name }} </td>
                                        @if ($transferReport->operation == 'send')
                                            <td>
                                                <span class="tag text-green has-text-weight-medium">
                                                    <span class="icon">
                                                        <i class="fas fa-plane-departure"></i>
                                                    </span>
                                                    <span> Send </span>
                                                </span>
                                            </td>
                                        @else
                                            <td>
                                                <span class="tag text-purple has-text-weight-medium">
                                                    <span class="icon">
                                                        <i class="fas fa-plane-arrival"></i>
                                                    </span>
                                                    <span> Received </span>
                                                </span>
                                            </td>
                                        @endif
                                        <td class="has-text-right"> {{ number_format($transferReport->quantity, 2) . ' ' . $transferReport->unit_of_measurement }} </td>
                                        <td> {{ $transferReport->issued_on->toFormattedDateString() }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endcan
        @endif

        @if (isFeatureEnabled('Return Management'))
            @can('Read Return')
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-arrow-alt-circle-left"></i>
                                </span>
                                <span>Returns Report</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Branch </abbr></th>
                                <th><abbr> Product </abbr></th>
                                <th class="has-text-right"><abbr> Quantity </abbr></th>
                                <th><abbr> Date </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($inventorySummaryReport->getReturnReports as $returnReport)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $returnReport->branch_name }} </td>
                                        <td> {{ $returnReport->product_name }} </td>
                                        <td class="has-text-right"> {{ number_format($returnReport->quantity, 2) . ' ' . $returnReport->unit_of_measurement }} </td>
                                        <td> {{ $returnReport->issued_on->toFormattedDateString() }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endcan
        @endif

        @if (isFeatureEnabled('Gdn Management'))
            @can('Read GDN')
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-file-invoice"></i>
                                </span>
                                <span>Delivery Orders Report</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Branch </abbr></th>
                                <th><abbr> Product </abbr></th>
                                <th class="has-text-right"><abbr> Quantity </abbr></th>
                                <th><abbr> Date </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($inventorySummaryReport->getGdnReports as $gdnReport)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $gdnReport->branch_name }} </td>
                                        <td> {{ $gdnReport->product_name }} </td>
                                        <td class="has-text-right"> {{ number_format($gdnReport->quantity, 2) . ' ' . $gdnReport->unit_of_measurement }} </td>
                                        <td> {{ $gdnReport->issued_on->toFormattedDateString() }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endcan
        @endif

        @if (isFeatureEnabled('Sale Management'))
            @can('Read Sale')
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-cash-register"></i>
                                </span>
                                <span>Invoices Report</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Branch </abbr></th>
                                <th><abbr> Product </abbr></th>
                                <th class="has-text-right"><abbr> Quantity </abbr></th>
                                <th><abbr> Date </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($inventorySummaryReport->getSaleReports as $saleReport)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $saleReport->branch_name }} </td>
                                        <td> {{ $saleReport->product_name }} </td>
                                        <td class="has-text-right"> {{ number_format($saleReport->quantity, 2) . ' ' . $saleReport->unit_of_measurement }} </td>
                                        <td> {{ $saleReport->issued_on->toFormattedDateString() }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endcan
        @endif

        @if (isFeatureEnabled('Reservation Management'))
            @can('Read Reservation')
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-archive"></i>
                                </span>
                                <span>Reservations Report</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Branch </abbr></th>
                                <th><abbr> Product </abbr></th>
                                <th class="has-text-right"><abbr> Quantity </abbr></th>
                                <th><abbr> Date </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($inventorySummaryReport->getReservationReports as $reservationReport)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $reservationReport->branch_name }} </td>
                                        <td> {{ $reservationReport->product_name }} </td>
                                        <td class="has-text-right"> {{ number_format($reservationReport->quantity, 2) . ' ' . $reservationReport->unit_of_measurement }} </td>
                                        <td> {{ $reservationReport->issued_on->toFormattedDateString() }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endcan
        @endif

        @foreach (pads() as $pad)
            @continue ($pad->isInventoryOperationNone())

            @canpad('Read', $pad->id)
            <div class="column is-6 p-lr-0">
                <x-content.header bg-color="has-background-white">
                    <x-slot:header>
                        <h1 class="title text-green has-text-weight-medium is-size-6">
                            <span class="icon mr-1">
                                <i class="{{ $pad->icon }}"></i>
                            </span>
                            <span>{{ $pad->name }} Report</span>
                        </h1>
                    </x-slot:header>
                </x-content.header>
                <x-content.footer>
                    <x-common.client-datatable
                        has-filter="false"
                        has-length-change="false"
                        paging-type="simple"
                        length-menu=[5]
                    >
                        <x-slot name="headings">
                            <th><abbr> # </abbr></th>
                            <th><abbr> Branch </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th class="has-text-right"><abbr> Quantity </abbr></th>
                            <th><abbr> Date </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($inventorySummaryReport->getTransactionReports($pad->id) as $transactionReport)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $transactionReport->branch_name }} </td>
                                    <td> {{ $transactionReport->product_name }} </td>
                                    <td class="has-text-right"> {{ number_format($transactionReport->quantity, 2) . ' ' . $transactionReport->unit_of_measurement }} </td>
                                    <td> {{ $transactionReport->issued_on->toFormattedDateString() }} </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-common.client-datatable>
                </x-content.footer>
            </div>
            @endcanpad
        @endforeach
    </div>
@endsection

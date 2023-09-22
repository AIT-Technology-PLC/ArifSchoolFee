@extends('layouts.app')

@section('title', 'Inventory Batch Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter action="{{ route('reports.inventory_batch') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    <div class="column is-6">
                        <x-forms.label>
                            Availability
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="availability"
                                    name="availability"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option
                                        disabled
                                        selected
                                    > Availability </option>
                                    <option
                                        value="Available"
                                        @selected(request('availability') == 'Available')
                                    >
                                        Available
                                    </option>
                                    <option
                                        value="Out of Stock"
                                        @selected(request('availability') == 'Out of Stock')
                                    >
                                        Out of Stock
                                    </option>
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Expiry
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="expiry"
                                    name="expiry"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option
                                        disabled
                                        selected
                                    > Expiry </option>
                                    <option
                                        value="Expired"
                                        @selected(request('Expiry') == 'Expired')
                                    >
                                        Expired
                                    </option>
                                    <option
                                        value="Near Expiry"
                                        @selected(request('Expiry') == 'Near Expiry')
                                    >
                                        Near Expiry
                                    </option>
                                    <option
                                        value="Usable"
                                        @selected(request('Expiry') == 'Usable')
                                    >
                                        Usable
                                    </option>
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
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
                                        >
                                            {{ $product->name }}
                                            @if (!empty($product->code))
                                                ({{ $product->code }})
                                            @endif
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-large is-left"
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
                            <i class="fas fa-th"></i>
                        </span>
                        <span>
                            Inventory Batch
                        </span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                {{ $dataTable->table() }}
            </x-content.footer>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

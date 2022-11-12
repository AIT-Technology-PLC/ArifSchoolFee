@extends('layouts.app')

@section('title', 'Inventory Level Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter action="{{ route('reports.inventory_level') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    <div class="column is-6">
                        <x-forms.label>
                            Date
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.input
                                    class="is-size-7-mobile"
                                    type="date"
                                    name="date"
                                    id="date"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ request('date') ?? now()->toDateString() }}"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Period
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="period_type"
                                    name="period_type"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Period </option>
                                    <option
                                        value="ending"
                                        @selected(request('period_type') == 'ending')
                                    >
                                        Ending Inventory
                                    </option>
                                    <option
                                        value="beginning"
                                        @selected(request('period_type') == 'beginning')
                                    >
                                        Beginning Inventory
                                    </option>
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </x-common.report-filter>
    <section class="mx-3 m-lr-0 mt-3">
        <div class="box radius-top-0">
            <div>
                {{ $dataTable->table() }}
            </div>
        </div>
    </section>
    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush
@endsection

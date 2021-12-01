@extends('layouts.app')

@section('title', 'Delivery Orders')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="Del. Orders"
                :amount="$totalGdns"
                icon="fas fa-file-invoice"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalSubtracted"
                border-color="#3d8660"
                text-color="text-green"
                label="Subtracted"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotSubtracted"
                border-color="#86843d"
                text-color="text-gold"
                label="Approved"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotApproved"
                border-color="#863d63"
                text-color="text-purple"
                label="Waiting Approval"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Delivery Orders">
            @can('Create GDN')
                <x-common.button
                    tag="a"
                    href="{{ route('gdns.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Delivery Order"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-datatables.filter filters="'pad', 'status'">
                <div class="columns is-marginless is-vcentered">
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-small is-fullwidth"
                                    x-model="filters.pad"
                                    x-on:change="add('pad')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Pads
                                    </option>
                                    <option value="all"> All Pads </option>
                                    @foreach (auth()->user()->getAllowedWarehouses('transactions') as $warehouse)
                                        <option value="{{ $warehouse->id }}"> {{ $warehouse->name }} Pad </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-small is-fullwidth"
                                    x-model="filters.status"
                                    x-on:change="add('status')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Statuses
                                    </option>
                                    <option value="all"> All Statuses </option>
                                    @foreach (['Waiting Approval', 'Approved', 'Subtracted'] as $status)
                                        <option value="{{ Str::lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

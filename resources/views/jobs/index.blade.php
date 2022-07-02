@extends('layouts.app')

@section('title', 'Jobs')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Jobs"
                :amount="$totalJobs"
                icon="fas fa-cogs"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalApproved"
                border-color="#3d8660"
                text-color="text-green"
                label="Approved"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotApproved"
                border-color="#86843d"
                text-color="text-gold"
                label="Waiting Approval"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Jobs">
            @can('Create Job')
                <x-common.button
                    tag="a"
                    href="{{ route('jobs.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Job"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.fail-message :message="session('failedMessage')" />
            <x-datatables.filter filters="'progress', 'status', 'type'">
                <div class="columns is-marginless is-vcentered">
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.progress"
                                    x-on:change="add('progress')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Progress
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Completed', 'In Process', 'Not Started'] as $progress)
                                        <option value="{{ str()->lower($progress) }}"> {{ $progress }} </option>
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
                                    class="is-size-7-mobile is-fullwidth"
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
                                    <option value="all"> All </option>
                                    @foreach (['Approved', 'Waiting Approval'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
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
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.type"
                                    x-on:change="add('type')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Type
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Inventory Replenishment', 'Customer Order'] as $type)
                                        <option value="{{ str()->lower($type) }}"> {{ $type }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

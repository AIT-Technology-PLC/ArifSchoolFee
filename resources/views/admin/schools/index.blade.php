@extends('layouts.app')

@section('title', 'Schools')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Schools"
                :amount="$schools"
                icon="fas fa-graduation-cap"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Enabled"
                box-color="bg-lightblue"
                :amount="$enabledSchools"
                icon="fas fa-check"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Deactivated"
                box-color="bg-red"
                :amount="$disabledSchools"
                icon="fas fa-ban"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header
            title="Schools"
            is-mobile
        >
            @can('Manage Admin Panel Companies')
                <x-common.button
                    tag="a"
                    href="{{ route('admin.schools.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create School"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            <x-datatables.filter filters="'status','plans', 'types'">
                <div class="columns is-marginless is-vcentered">
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
                                        Status
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Active', 'Deactivated'] as $status)
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
                                    x-model="filters.plans"
                                    x-on:change="add('plans')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Plans
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}"> {{ $plan->name }} </option>
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
                                    x-model="filters.types"
                                    x-on:change="add('types')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Types
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}"> {{ $type->name }} </option>
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

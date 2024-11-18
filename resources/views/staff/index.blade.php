@extends('layouts.app')

@section('title', 'Staffs')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-12 p-lr-0">
            <x-common.total-model
                model="Staff"
                :amount="$totalStaff"
                icon="fas fa-user-group"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Staff Information">
            @can('Import Staff')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Staffs"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
            @can('Create Branch')
                <x-common.button
                    tag="a"
                    href="{{ route('staff.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Add Staff"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="session('limitReachedMessage') ?? (count($errors->all()) ? $errors->all() : null)" />
            <x-datatables.filter filters="'branch', 'department', 'designation'">
                <div class="columns is-marginless is-vcentered">
                    <div class="column is-4 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.branch"
                                    x-on:change="add('branch')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Branches
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id}}"> {{$branch->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.department"
                                    x-on:change="add('department')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Departments
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id}}"> {{$department->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.designation"
                                    x-on:change="add('designation')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Designations
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id}}"> {{$designation->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            <div> {{ $dataTable->table() }} </div>
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Staff')
        <x-common.import
            title="Import Staff"
            action="{{ route('staffs.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

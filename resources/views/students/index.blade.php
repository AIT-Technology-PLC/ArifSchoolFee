@extends('layouts.app')

@section('title', 'Students')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-12 p-lr-0">
            <x-common.total-model
                model="Student"
                :amount="$totalStudents"
                icon="fas fa-user-graduate"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Student Information">
            @can('Import Student')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Students"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
            @can('Create Student')
                <x-common.button
                    tag="a"
                    href="{{ route('students.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Add Student"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="session('limitReachedMessage') ?? session('failedMessage') ??  (count($errors->all()) ? $errors->all() : null)" />
            <x-datatables.filter filters="'branch', 'class', 'section'">
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
                                    x-model="filters.class"
                                    x-on:change="add('class')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                      Select Class
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id}}"> {{$class->name }} </option>
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
                                    x-model="filters.section"
                                    x-on:change="add('section')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Select Section
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id}}"> {{$section->name }} </option>
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
    @can('Import Student')
        <x-common.import
            title="Import Student"
            action="{{ route('students.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

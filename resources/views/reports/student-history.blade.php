@extends('layouts.app')

@section('title', 'Student History Report')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-blue has-text-weight-medium is-size-5">
                    Student History
                </h1>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            <x-datatables.filter filters="'branches','classes','sections', 'academicYears'">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.branches"
                                    x-on:change="add('branches')"
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
                                        <option value="{{ $branch->id }}"> {{ $branch->name }} </option>
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
                                    x-model="filters.classes"
                                    x-on:change="add('classes')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Class
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"> {{ $class->name }} </option>
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
                                    x-model="filters.sections"
                                    x-on:change="add('sections')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Sections
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"> {{ $section->name }} </option>
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
                                    x-model="filters.academicYears"
                                    x-on:change="add('academicYears')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Academic Year
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach ($academicYears as $academicYear)
                                        <option value="{{ $academicYear->id }}"> {{ $academicYear->year }} </option>
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
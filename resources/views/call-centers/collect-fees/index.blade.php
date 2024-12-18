@extends('layouts.app')

@section('title', 'Collect Fees')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-hand-holding-dollar" />
                    <span>
                        Collect Fees
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            <x-datatables.filter filters="'school','branch', 'schoolClass', 'section', 'other'">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="school"
                                    name="school"
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.school"
                                    x-on:change="add('school')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Select School
                                    </option>
                                    @foreach ($schools as $school)
                                        <option value="{{ $school->id}}"> {{$school->name }} ({{$school->company_code }}) </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="branch"
                                    name="branch"
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.branch"
                                    x-on:change="add('branch')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Select Branch
                                    </option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id}}"> {{$branch->name }} ({{$branch->company->company_code }}) </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="school_class_id"
                                    name="school_class_id"
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.schoolClass"
                                    x-on:change="add('schoolClass')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Select Class
                                    </option>
                                    @foreach ($classes as $schoolClass)
                                        <option value="{{ $schoolClass->id}}"> {{$schoolClass->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="section_id"
                                    name="section_id"
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
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id}}"> {{$section->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6 p-lr-0 pt-0">
                        <x-forms.field>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id=""
                                    name=""
                                    type="text"
                                    placeholder="Search by Name, Admission No, Phone"
                                    x-model="filters.other"
                                    x-on:change="add('other')"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-large is-left"
                                />
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

    <script src="{{ asset('js/steps-component.js') }}"></script>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush




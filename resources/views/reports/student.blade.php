@extends('layouts.app')

@section('title', 'Student Report')

@section('content')
    <x-common.report-filter action="{{ route('reports.student') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
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
                                    @foreach ($branches as $branch)
                                        <option
                                            value="{{ $branch->id }}"
                                            @selected(request('branches') == $branch->id)
                                        > {{ $branch->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Academic Year
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="academicYears"
                                    name="academicYears"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Academic Year </option>
                                    <option
                                        value=""
                                        @selected(request('academicYears') == '')
                                    > All </option>
                                    @foreach ($academicYears as $academicYear)
                                        <option
                                            value="{{ $academicYear->id }}"
                                            @selected(request('academicYears') == $academicYear->id)
                                        > {{ $academicYear->year }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Class
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="classes"
                                    name="classes"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Class </option>
                                    <option
                                        value=""
                                        @selected(request('classes') == '')
                                    > All </option>
                                    @foreach ($classes as $class)
                                        <option
                                            value="{{ $class->id }}"
                                            @selected(request('classes') == $class->id)
                                        > {{ $class->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Section
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="sections"
                                    name="sections"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Sections </option>
                                    <option
                                        value=""
                                        @selected(request('sections') == '')
                                    > All </option>
                                    @foreach ($sections as $section)
                                        <option
                                            value="{{ $section->id }}"
                                            @selected(request('sections') == $section->id)
                                        > {{ $section->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </x-common.report-filter>

    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$studentReport->getTotalStudents"
                border-color="#86843d"
                text-color="text-blue"
                label="Total Students"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$studentReport->getStudentsByRouteUsage['uses_route']"
                border-color="#86843d"
                text-color="text-blue"
                label="Students Using Transport"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$studentReport->getStudentsByRouteUsage['does_not_use_route']"
                border-color="#86843d"
                text-color="text-blue"
                label="Students Not Using Transport"
            />
        </div>
       
        <div class="column is-12-mobile is-12-tablet is-6-desktop">
            <div class="box  has-text-white is-borderless">
                {!! $chartS->container() !!}
            </div>
        </div>
        
        <div class="column is-12-mobile is-12-tablet is-6-desktop">
            <div class="box  has-text-white is-borderless">
                {!! $chart->container() !!}
            </div>
        </div>

        <div class="column is-12 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-blue has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-user-graduate"></i>
                        </span>
                        <span>Student List</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="true"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu=[5]
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Admission No </abbr></th>
                        <th><abbr> Student Name </abbr></th>
                        <th><abbr> Gender </abbr></th>
                        <th><abbr> Phone </abbr></th>
                        <th><abbr> Address </abbr></th>
                        <th><abbr> Branch </abbr></th>
                        <th><abbr> Class </abbr></th>
                        <th><abbr> Transport </abbr></th>
                        <th><abbr> Academic Year </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($studentReport->getStudentList() as $student)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $student->code ?? 'N/A' }} </td>
                                <td> {{ $student->first_name ?? '' }} {{ $student->father_name ?? '' }} </td>                                
                                <td> {{ ucfirst($student->gender) ?? 'N/A' }} </td>
                                <td> {{ $student->phone ?? 'N/A' }} </td>
                                <td> {{ $student->current_address ? ($student->current_address ?? 'N/A') : ($student->permanent_address ?? 'N/A')}} </td>
                                <td> {{ $student->warehouse_name ?? 'N/A' }} </td>
                                <td> {{ $student->school_class_name ?? 'N/A' }} ({{ $student->section_name ?? 'N/A' }}) </td>
                                <td> {{ $student->route_name ?? 'N/A' }} ({{ $student->vehicle_number ?? 'N/A' }}) </td>
                                <td> {{ $student->academic_year_name ?? 'N/A' }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
    <script src="{{ $chartS->cdn() }}"></script>
    {{ $chartS->script() }}
@endsection

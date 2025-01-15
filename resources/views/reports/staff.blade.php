@extends('layouts.app')

@section('title', 'Staff Report')

@section('content')
    <x-common.report-filter action="{{ route('reports.staff') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    <div class="column is-12">
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
                    <div class="column is-12">
                        <x-forms.label>
                            Designation
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="designations"
                                    name="designations"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Designations </option>
                                    <option
                                        value=""
                                        @selected(request('designations') == '')
                                    > All </option>
                                    @foreach ($designations as $designation)
                                        <option
                                            value="{{ $designation->id }}"
                                            @selected(request('designations') == $designation->id)
                                        > {{ $designation->year }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.label>
                            Department
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="departments"
                                    name="departments"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Departments </option>
                                    <option
                                        value=""
                                        @selected(request('departments') == '')
                                    > All </option>
                                    @foreach ($departments as $department)
                                        <option
                                            value="{{ $department->id }}"
                                            @selected(request('departments') == $department->id)
                                        > {{ $department->name }} </option>
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
                :amount="$staffReport->getTotalStaff"
                border-color="#86843d"
                text-color="text-blue"
                label="Total Staff"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$staffReport->getStaffByGenderUsage['female']"
                border-color="#86843d"
                text-color="text-blue"
                label="Female"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$staffReport->getStaffByGenderUsage['male']"
                border-color="#86843d"
                text-color="text-blue"
                label="Male"
            />
        </div>
       
        <div class="column is-12-mobile is-12-tablet is-4-desktop">
            <div class="box  has-text-white is-borderless">
                {!! $chart->container() !!}
            </div>
        </div>
        <div class="column is-12-mobile is-12-tablet is-4-desktop">
            <div class="box  has-text-white is-borderless">
                {!! $chartT->container() !!}
            </div>
        </div>
        <div class="column is-12-mobile is-12-tablet is-4-desktop">
            <div class="box  has-text-white is-borderless">
                {!! $chartS->container() !!}
            </div>
        </div>

        <div class="column is-12 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-blue has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-users"></i>
                        </span>
                        <span>Staff List</span>
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
                        <th><abbr> Staff No </abbr></th>
                        <th><abbr> Full Name </abbr></th>
                        <th><abbr> Gender </abbr></th>
                        <th><abbr> Phone </abbr></th>
                        <th><abbr> Email </abbr></th>
                        <th><abbr> Designation </abbr></th>
                        <th><abbr> Branch </abbr></th>
                        <th><abbr> Department </abbr></th>
                        <th><abbr> Marital Status </abbr></th>
                        <th><abbr> Address </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($staffReport->getStaffList() as $staff)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $staff->code ?? 'N/A' }} </td>
                                <td> {{ $staff->first_name ?? '' }} {{ $staff->father_name ?? '' }} </td>                                
                                <td> {{ ucfirst($staff->gender) ?? 'N/A' }} </td>
                                <td> {{ $staff->phone ?? 'N/A' }} </td>
                                <td> {{ $staff->email ?? 'N/A' }} </td>
                                <td> {{ $staff->designation_name ?? 'N/A' }} </td>
                                <td> {{ $staff->warehouse_name ?? 'N/A' }} </td>
                                <td> {{ $staff->department_name ?? 'N/A' }} </td>
                                <td> {{ $staff->marital_status ?? 'N/A' }} </td>
                                <td> {{ $staff->current_address ? ($staff->current_address ?? 'N/A') : ($student->permanent_address ?? 'N/A')}} </td>
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
    <script src="{{ $chartT->cdn() }}"></script>
    {{ $chartT->script() }}
@endsection

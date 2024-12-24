@extends('layouts.app')

@section('title', 'Student Promote')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-check" />
                    <span>
                        Promote Student To The Next Academic Year
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            <x-datatables.filter filters="'branch', 'academicYear' , 'class', 'section'">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-3 p-lr-0 pt-0">
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
                                        Select Branch
                                    </option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id}}"> {{$branch->name }} </option>
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
                                    x-model="filters.academicYear"
                                    x-on:change="add('academicYear')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Select Academic year
                                    </option>
                                    @foreach ($academicYears as $academicYear)
                                        <option value="{{ $academicYear->id}}"> {{$academicYear->year }} </option>
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
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id}}"> {{$class->name }} </option>
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
                </div>
            </x-datatables.filter>
            <form
                id="formOne"
                action="{{ route('student-promotes.store') }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <div> 
                    {{ $dataTable->table() }} 

                    <div id="bottomFormField" class="is-hidden">
                        <hr>
                        <div class="columns is-marginless is-multiline is-mobile">
                            <div class="column is-6-mobile is-3-tablet is-3-desktop">
                                <x-forms.field>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="warehouse_id"
                                            name="warehouse_id"
                                        >
                                        <option 
                                            selected
                                            disabled
                                        >Select Branch</option>
                                            @foreach ($branches as $branch)
                                                <option
                                                    value="{{ $branch->id }}"
                                                    {{ old('warehouse_id') == $branch->id ? 'selected' : '' }}
                                                >{{ $branch->name }}</option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-sort"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="warehouse_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6-mobile is-3-tablet is-3-desktop">
                                <x-forms.field>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="academic_year_id"
                                            name="academic_year_id"
                                        >
                                        <option 
                                            selected
                                            disabled
                                        >Select Acadmeic year</option>
                                            @foreach ($academicYears as $academicYear)
                                                <option
                                                    value="{{ $academicYear->id }}"
                                                    {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}
                                                >{{ $academicYear->year }}</option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-sort"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="academic_year_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6-mobile is-3-tablet is-3-desktop">
                                <x-forms.field>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="school_class_id"
                                            name="school_class_id"
                                        >
                                        <option 
                                            selected
                                            disabled
                                        >Select Class</option>
                                            @foreach ($classes as $class)
                                                <option
                                                    value="{{ $class->id }}"
                                                    {{ old('school_class_id') == $class->id ? 'selected' : '' }}
                                                >{{ $class->name }}</option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-sort"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="school_class_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6-mobile is-3-tablet is-3-desktop">
                                <x-forms.field>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="section_id"
                                            name="section_id"
                                        >
                                        <option 
                                            selected
                                            disabled
                                        >Select Section</option>
                                            @foreach ($sections as $section)
                                                <option
                                                    value="{{ $section->id }}"
                                                    {{ old('section_id') == $section->id ? 'selected' : '' }}
                                                >{{ $section->name }}</option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-sort"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="section_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        </div>
                        <x-common.assign-button label="Promote" icon="fa-check" />
                    </div>
                </div>
            </form>
        </x-content.footer>
    </x-common.content-wrapper>

    <script src="{{ asset('js/steps-component.js') }}"></script>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        $(document).ready(function() {
            var table = $('#student-promotes-datatable').DataTable();

            function toggleTableVisibility() {
                if (table.rows().count() > 0) {
                    $('#bottomFormField').removeClass('is-hidden'); 
                } else {
                    $('#bottomFormField').addClass('is-hidden');
                }
            }

            toggleTableVisibility();

            table.on('draw', function() {
                toggleTableVisibility();
            });
        });
    </script>
@endpush



@extends('layouts.app')

@section('title', 'Assign Fee Master')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-tasks" />
                    <span>
                        Selection Criteria ( {{ $assignFee->feeType->name }} - {{ $assignFee->amount }} )
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            <x-datatables.filter filters="'branch', 'class', 'section', 'category', 'gender'">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-2.4 p-lr-0 pt-0">
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
                    <div class="column is-2.4 p-lr-0 pt-0">
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
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id}}"> {{$class->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-2.4 p-lr-0 pt-0">
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
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id}}"> {{$section->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-2.4 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.category"
                                    x-on:change="add('category')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Select Category
                                    </option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id}}"> {{$category->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-2.4 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.gender"
                                    x-on:change="add('gender')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Select Gender
                                    </option>
                                    @foreach (['male', 'female'] as $gender)
                                        <option value="{{ str()->lower($gender) }}"> {{ucfirst($gender) }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            <form
                id="formOne"
                action="{{ route('assign-fees.update', $assignFee->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                @method('PATCH')
                    <div id="dataTableContainer" class="is-hidden"> 
                        {{ $dataTable->table() }} 
                        <x-common.assign-button label="Assign Fee" />
                    </div>
            </form>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        $(document).ready(function() {
            var table = $('#assign-fees-datatable').DataTable();

            function toggleTableVisibility() {
                if (table.rows().count() > 0) {
                    $('#dataTableContainer').removeClass('is-hidden'); 
                } else {
                    $('#dataTableContainer').addClass('is-hidden');
                }
            }

            toggleTableVisibility();

            table.on('draw', function() {
                toggleTableVisibility();
            });
        });
    </script>
@endpush



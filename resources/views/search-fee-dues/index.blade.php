@extends('layouts.app')

@section('title', 'Search Fees Due')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-search" />
                    <span>
                        Search Fees Due
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            <x-datatables.filter filters="'branch', 'class', 'section', 'group'">
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
                                    x-model="filters.group"
                                    x-on:change="add('group')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Select Fee Group
                                    </option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id}}"> {{$group->name }} </option>
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




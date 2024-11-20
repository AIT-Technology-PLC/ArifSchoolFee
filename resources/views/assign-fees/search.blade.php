@extends('layouts.app')

@section('title', 'Assign Fee Master')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-tasks" />
                    <span>
                        Selection Criteria
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('assign-fees.search', $feeMaster->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.footer>
                <x-datatables.filter filters="'branch', 'class', 'section', 'category', 'gender'">
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-2.4 p-lr-0 pt-0">
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id="branch"
                                        name="branch"
                                        class="is-size-7-mobile is-fullwidth"
                                        x-on:change="add('branch')"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Branch
                                        </option>
                                        @foreach ($branches as $branch)
                                            <option
                                                value="{{ $branch->id }}"
                                                @selected($branch->id == $branchId)
                                            >
                                                {{$branch->name }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-2.4 p-lr-0 pt-0">
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id="class"
                                        name="class"
                                        class="is-size-7-mobile is-fullwidth"
                                        x-on:change="add('class')"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Class
                                        </option>
                                        @foreach ($classes as $class)
                                            <option
                                                value="{{ $class->id }}"
                                                @selected($class->id == $classId)
                                            >
                                                {{$class->name }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-2.4 p-lr-0 pt-0">
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id="section"
                                        name="section"
                                        class="is-size-7-mobile is-fullwidth"
                                        x-on:change="add('section')"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Section
                                        </option>
                                        @foreach ($sections as $section)
                                            <option
                                                value="{{ $section->id }}"
                                                @selected($section->id == $sectionId)
                                            >
                                                {{$section->name }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-2.4 p-lr-0 pt-0">
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id="category"
                                        name="category"
                                        class="is-size-7-mobile is-fullwidth"
                                        x-on:change="add('category')"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Category
                                        </option>
                                        @foreach ($categories as $category)
                                            <option
                                                value="{{ $category->id }}"
                                                @selected($category->id == $categoryId)
                                            >
                                                {{$category->name }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-2.4 p-lr-0 pt-0">
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id="gender"
                                        name="gender"
                                        class="is-size-7-mobile is-fullwidth"
                                        x-on:change="add('gender')"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Gender
                                        </option>
                                        @foreach (['male', 'female'] as $gender)
                                            <option value="{{str()->lower($gender) }}"
                                                @selected($gender == $genderValue)
                                            >
                                                {{ ucfirst($gender) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    </div>
                </x-datatables.filter>
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

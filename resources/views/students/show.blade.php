@extends('layouts.app')

@section('title', 'Student Details')

@section('content')
    <div class="columns is-marginless is-multiline"> 
        <div class="column is-12" >
            <article class="panel bg-white">
                <div class="panel-heading level bg-softblue">
                    <span class="tag bg-softblue has-text-white has-text-weight-bold ml-1 m-lr-0">
                        <x-common.icon name="fas fa-graduation-cap" />
                        <span>
                            Student Detail
                        </span>
                    </span>
                    <x-common.dropdown  name="Actions">
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="a"
                                href="{{ route('students.edit', $student->id) }}"
                                mode="button"
                                icon="fas fa-pen"
                                label="Edit"
                                class="has-text-weight-medium is-small text-blue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    </x-common.dropdown>
                </div>

                <p class="panel-tabs mx-3" id="tabs">
                    <a class="is-active" data-target="basic-information">Basic Information</a>
                    <a data-target="personal-detail">Personal Detail</a>
                    <a data-target="address-and-transport-detail">Address and Transport</a>
                </p>
                <br/>

                <div class="mx-4">
                    <x-common.success-message :message="session('successMessage')" />
                </div>

                <div id="basic-information" class="panel-block is-active">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-half-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-hashtag"
                                :data="$student->code ?? 'N/A'"
                                label="Admission No" 
                            />
                        </div>
                        <div class="column is-half-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-code-branch"
                                :data="$student->warehouse->name ? ucfirst($student->warehouse->name) : 'N/A'"
                                label="Branch" 
                            />
                        </div>
                        <div class="column is-half-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-chalkboard"
                                :data="$student->schoolClass->name ? ucfirst($student->schoolClass->name) : 'N/A'"
                                label="Class" 
                            />
                        </div>
                        <div class="column is-half-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-list"
                                :data="$student->section->name ? ucfirst($student->section->name) : 'N/A'"
                                label="Section" 
                            />
                        </div>
                        <div class="column is-half-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-th-list"
                                :data="$student->studentCategory->name ? ucfirst($student->studentCategory->name . ' (' . str($student->studentCategory->description)->stripTags()->limit(20) . ')') : 'N/A'"
                                label="Student Category" 
                            />
                        </div>
                        <div class="column is-half-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-user-friends"
                                :data="$student->studentGroup->name ? ucfirst($student->studentGroup->name) : 'N/A'"
                                label="Student Group" 
                            />
                        </div>
                    </div>
                </div>

                <div id="personal-detail" class="panel-block">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fa fa-user"
                                :data="$student->first_name ? ucfirst($student->first_name) : 'N/A'"
                                label="First Name"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-mars"
                                :data="$student->father_name ? ucfirst($student->father_name) : 'N/A'"
                                label="Father Name"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-user"
                                :data="$student->last_name ? ucfirst($student->last_name) : 'N/A'"
                                label="Last Name"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-venus"
                                :data="$student->mother_name ? ucfirst($student->mother_name) : 'N/A'"
                                label="Mother Name"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                            icon="fa fa-user"
                            :data="$student->gender ? ucfirst($student->gender) : 'N/A'"
                            label="Gender"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-phone"
                                :data="$student->phone ?? 'N/A'"
                                label="Phone"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fa fa-envelope"
                                :data="$student->email ?? 'N/A'"
                                label="Email"
                            />
                        </div> 
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fa fa-calendar-alt"
                                :data="$student->date_of_birth?->toFormattedDateString() ?? 'N/A'"
                                label="Date Of Birth"
                            />
                        </div>
                    </div>
                </div>

                <div id="address-and-transport-detail" class="panel-block">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fa fa-calendar-alt"
                                :data="$student->academicYear->year ?? 'N/A'"
                                label="Academic year"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fa fa-calendar-alt"
                                :data="$student->admission_date ?? 'N/A'"
                                label="Admission Date"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-route"
                                :data="$student->route->title ?? 'N/A'"
                                label="Route"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-bus"
                                :data="$student->vehicle->vehicle_number ?? 'N/A'"
                                label="Vehicle"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-globe"
                                :data="$student->current_address ?? 'N/A'"
                                label="Current Address"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-globe"
                                :data="$student->permanent_address ?? 'N/A'"
                                label="Permanent Address"
                            />
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('#tabs a');
        const panels = document.querySelectorAll('.panel-block');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(item => item.classList.remove('is-active'));
                tab.classList.add('is-active');

                const target = tab.getAttribute('data-target');
                panels.forEach(panel => {
                    if (panel.id === target) {
                        panel.classList.add('is-active');
                    } else {
                        panel.classList.remove('is-active');
                    }
                });
            });
        });
    });
</script>
@endsection

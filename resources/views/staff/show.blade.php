@extends('layouts.app')

@section('title', 'Staff Details')

@section('content')
    <div class="columns is-marginless is-multiline"> 
        <div class="column is-12" >
            <article class="panel bg-white">
                <div class="panel-heading level bg-softblue">
                    <span class="tag bg-softblue has-text-white has-text-weight-bold ml-1 m-lr-0">
                        <x-common.icon name="fas fa-users" />
                        <span>
                            Staff Detail
                        </span>
                    </span>
                    <x-common.dropdown  name="Actions">
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="a"
                                href="{{ route('staff.edit', $staff->id) }}"
                                mode="button"
                                icon="fas fa-pen"
                                label="Edit"
                                class="has-text-weight-medium is-small text-blue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    </x-common.dropdown>
                </div>

                <p class="panel-tabs mx-3" id="tabs">
                    <a class="is-active" data-target="personal-detail">Personal Detail</a>
                    <a data-target="payroll-detail">Payroll Information</a>
                </p>
                <br/>

                <div class="mx-4">
                    <x-common.success-message :message="session('successMessage')" />
                </div>

                <div id="personal-detail" class="panel-block is-active">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-half-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-hashtag"
                                :data="$staff->code ?? 'N/A'"
                                label="Staff No" 
                            />
                        </div>
                        <div class="column is-half-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-code-branch"
                                :data="$staff->warehouse->name ? ucfirst($staff->warehouse->name) : 'N/A'"
                                label="Branch" 
                            />
                        </div>
                        <div class="column is-half-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-user-group"
                                :data="$staff->department->name ? ucfirst($staff->department->name) : 'N/A'"
                                label="Department" 
                            />
                        </div>
                        <div class="column is-half-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-briefcase"
                                :data="$staff->designation->name ? ucfirst($staff->designation->name) : 'N/A'"
                                label="Designation" 
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fa fa-user"
                                :data="$staff->first_name ? ucfirst($staff->first_name) : 'N/A'"
                                label="First Name"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-venus"
                                :data="$staff->father_name ? ucfirst($staff->father_name) : 'N/A'"
                                label="Father Name"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-user"
                                :data="$staff->last_name ? ucfirst($staff->last_name) : 'N/A'"
                                label="Last Name"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-venus"
                                :data="$staff->mother_name ? ucfirst($staff->mother_name) : 'N/A'"
                                label="Mother Name"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                            icon="fa fa-user"
                            :data="$staff->gender ? ucfirst($staff->gender) : 'N/A'"
                            label="Gender"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-phone"
                                :data="$staff->phone ?? 'N/A'"
                                label="Phone"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fa fa-envelope"
                                :data="$staff->email ?? 'N/A'"
                                label="Email"
                            />
                        </div> 
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-heading"
                                :data="$staff->martial_status ? ucfirst($staff->martial_status) : 'N/A'"
                                label="Martial Status"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fa fa-calendar-alt"
                                :data="$staff->date_of_birth?->toFormattedDateString() ?? 'N/A'"
                                label="Date Of Birth"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fa fa-globe"
                                :data="$staff->current_address ?? 'N/A'"
                                label="Current Address"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-globe"
                                :data="$staff->permanent_address ?? 'N/A'"
                                label="Permanent Address"
                            />
                        </div>
                    </div>
                </div>

                <div id="payroll-detail" class="panel-block">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fa fa-calendar-alt"
                                :data="$staff->staffCompensations->date_of_joining ?? 'N/A'"
                                label="Date Of Joining"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-graduation-cap"
                                :data="$staff->staffCompensations->qualifications ?? 'N/A'"
                                label="Qualifications"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-briefcase"
                                :data="$staff->staffCompensations->experience ?? 'N/A'"
                                label="Experience"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-id-card"
                                :data="$staff->staffCompensations->efp_number ?? 'N/A'"
                                label="EPF Number"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-money-bill"
                                :data="money($staff->staffCompensations->basic_salary) ?? 'N/A'"
                                label="Basic Salary"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-file-contract"
                                :data="$staff->staffCompensations->job_type ? ucfirst($staff->staffCompensations->job_type) : 'N/A'"
                                label="Contract Type"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-globe"
                                :data="$staff->staffCompensations->location ?? 'N/A'"
                                label="Location"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-bank"
                                :data="$staff->staffCompensations->bank_name ?? 'N/A'"
                                label="Bank Name"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-wallet"
                                :data="$staff->staffCompensations->bank_account ?? 'N/A'"
                                label="Bank Account"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-code-branch"
                                :data="$staff->staffCompensations->branch_name ?? 'N/A'"
                                label="Branch Name"
                            />
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-common.show-data-section
                                icon="fas fa-hashtag"
                                :data="$staff->staffCompensations->tin_number ?? 'N/A'"
                                label="Tin Number"
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

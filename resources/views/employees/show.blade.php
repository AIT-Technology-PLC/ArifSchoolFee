@extends('layouts.app')

@section('title')
    Employee Profile - {{ $employee->user->name }}
@endsection

@section('content')
    <section class="m-3">
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-2">
                    <figure class="image is-square">
                        <img
                            class="is-rounded"
                            src="{{ asset('img/user.jpg') }}"
                        >
                    </figure>
                </div>
                <div class="column is-10">
                    <div class="level">
                        <div class="level-left pl-5">
                            <div class="level-item">
                                <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-user"></i> Name</span>
                                <span> {{ $employee->user->name }}</span>
                            </div>
                        </div>
                        <div class="level-right pr-5">
                            <div class="level-item">
                                <span class="is-size-7 text-green has-text-weight-bold mr-2">System Access</span>
                                <button class="button {{ $employee->enabled ? 'is-success' : 'is-danger' }} is-rounded is-small">{{ $employee->enabled ? 'Enabled' : 'Blocked' }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="level">
                        <div class="level-left pl-5">
                            <div class="level-item">
                                <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-cogs"></i> Role</span>
                                <span> {{ $employee->user->roles[0]->name }} </span>
                            </div>
                        </div>
                    </div>
                    <div class="level">
                        <div class="level-left pl-5">
                            <div class="level-item">
                                <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-users-rectangle"></i> Department</span>
                                <span> {{ $employee->department->name ?? 'No Department' }} </span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="level">
                        <div class="level-item mx-2">
                            <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-sort"></i> Job Type</span>
                            <span> {{ $employee->job_type }} </span>
                        </div>
                        <div class="level-item mx-2">
                            <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-user-tie"></i> Postion</span>
                            <span> {{ $employee->position }} </span>
                        </div>
                        <div class="level-item mx-2">
                            <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-calendar-alt"></i> Date of Hiring</span>
                            <span> {{ $employee->date_of_hiring?->toFormattedDateString() ?? 'No Info' }}</span>
                        </div>
                        <div class="level-item mx-2">
                            <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-umbrella-beach"></i> Leave Satatus</span>
                            <span> {{ now()->diffInDays($employee->leaves->last()->ending_period ?? now()) == 0 ? 'On Duty' : 'On Leave' }} </span>
                        </div>
                    </div>
                </div>
            </div>
        </x-content.footer>

        <div class="mb-5">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span>Personal Details</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <div class="level">
                    <div class="level-item mx-3">
                        <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-th"></i> Gender</span>
                        <span> {{ $employee->gender }} </span>
                    </div>
                    <div class="level-item mx-3">
                        <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-calendar-alt"></i> Date of Birth</span>
                        <span> {{ $employee->date_of_birth?->toFormattedDateString() ?? 'No Info' }} </span>
                    </div>
                    <div class="level-item mx-3">
                        <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-at"></i> Email</span>
                        <span> {{ $employee->user->email }}</span>
                    </div>
                    <div class="level-item mx-3">
                        <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-phone"></i> Phone</span>
                        <span> {{ $employee->phone }} </span>
                    </div>
                    <div class="level-item mx-3">
                        <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-phone"></i> Emergency Phone</span>
                        <span> {{ $employee->emergency_phone ?? 'No Contact' }} </span>
                    </div>
                </div>
                <div class="level">
                    <div class="level-item mx-3">
                        <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-user"></i> Emergency Name</span>
                        <span> {{ $employee->emergency_name ?? 'No name' }} </span>
                    </div>
                    <div class="level-item mx-3">
                        <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-map-marker-alt"></i> Address</span>
                        <span> {{ $employee->address }} </span>
                    </div>
                    <div class="level-item mx-2">
                        <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-spinner"></i> Leave Days Left</span>
                        <span> {{ now()->diffInDays($employee->leaves->last()->ending_period ?? now()) }} </span>
                    </div>
                    <div class="level-item mx-3">
                        <span class="is-size-7 text-green has-text-weight-bold mr-2"><i class="fas fa-hashtag"></i> Absent Days</span>
                        <span> {{ $employee->attendanceDetails->last()->days ?? 0 }} </span>
                    </div>
                </div>
            </x-content.footer>
        </div>

        <div>
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-circle-exclamation"></i>
                        </span>
                        <span>Warnings</span>
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
                        <th><abbr> Warning No </abbr></th>
                        <th><abbr> Type </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($employee->warnings as $warning)
                            <tr>
                                <td> {{ $warning->code }} </td>
                                <td> {{ $warning->type }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </section>
@endsection

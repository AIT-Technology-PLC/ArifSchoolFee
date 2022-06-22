@extends('layouts.app')

@section('title', 'Job Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-cogs"
                        :data="$job->code"
                        label="Job NO"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-spinner"
                        :data="$job->jobCompletionRate"
                        label="Progress"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$job->assignedTo->name"
                        label="Assigned To"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$job->customer->company_name ?? 'N/A'"
                        label="CUSTOMER"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-warehouse"
                        :data="$job->factory->name"
                        label="FACTORY"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$job->created_at->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$job->description"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="Details"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$job->isApproved())
                    @can('Approve Job')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('jobs.approve', $job->id)"
                                action="approve"
                                intention="approve this Job"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif($job->jobCompletionRate < 100)
                    @can('Update Job')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="button"
                                mode="button"
                                @click="$dispatch('open-update-wip-modal')"
                                icon="fa fa-plus"
                                label="Update Work in Process"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="button"
                                mode="button"
                                @click="$dispatch('open-update-available-modal')"
                                icon="fas fa-plus"
                                label="Update Finished Goods"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @can('Update Job')
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="button"
                            mode="button"
                            @click="$dispatch('open-create-job-extra-modal')"
                            icon="fas fa-plus"
                            label="Update Extra Materials"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endcan
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('jobs.edit', $job->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit"
                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
            @if (!$job->isApproved())
                <x-common.fail-message message="This Job has not been approved yet." />
            @elseif ($job->jobCompletionRate < 100)
                <x-common.fail-message message="Update Work in Process or Finished Goods." />
            @else
                <x-common.success-message message="Job Done." />
            @endif
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Extra Materials" />
        <x-content.footer>
            <x-common.fail-message :message="session('jobExtrafailedMessage')" />
            <x-common.success-message :message="session('jobExtraModified') ?? session('jobExtraDeleted')" />
            <x-common.bulma-table>
                <x-slot name="headings">
                    <th> # </th>
                    <th> Product </th>
                    <th> Status </th>
                    <th> Quantity </th>
                    <th> Type </th>
                    <th> Executed By </th>
                    <th> Action </th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($job->jobExtras as $jobExtra)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized">
                                {{ $jobExtra->product->name }}
                            </td>
                            <td>
                                {{ $jobExtra->status ?? 'Waiting' }}
                            </td>
                            <td>
                                {{ $jobExtra->quantity }}
                            </td>
                            <td>
                                {{ $jobExtra->type }}
                            </td>
                            <td>
                                {{ $jobExtra->executedBy->name }}
                            </td>
                            <td>
                                <x-common.action-buttons
                                    :buttons="['delete', 'edit']"
                                    model="job-extras"
                                    :id="$jobExtra->id"
                                />
                                @if ($jobExtra->type == 'Input' && !$jobExtra->isSubtracted())
                                    @can('Subtract Extra Job')
                                        <x-common.transaction-button
                                            :route="route('job-extras.subtract', $jobExtra->id)"
                                            action="subtract"
                                            intention="subtract this Job Materials"
                                            icon="fas fa-minus-circle"
                                            label="Subtract"
                                            class="tag is-white btn-purple is-small is-outlined  is-not-underlined"
                                        />
                                    @endcan
                                @endif
                                @if ($jobExtra->type == 'Remaining' && !$jobExtra->isAdded())
                                    @can('Add Extra Job')
                                        <x-common.transaction-button
                                            :route="route('job-extras.add', $jobExtra->id)"
                                            action="add"
                                            intention="add this Job Materials"
                                            icon="fas fa-plus"
                                            label="Add"
                                            class="tag is-white btn-green is-small is-outlined is-not-underlined"
                                        />
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.bulma-table>
        </x-content.footer>
    </x-common.content-wrapper>

    @can('Update Job')
        @include('jobs.partials.update-wip', ['jobDetails' => $job->jobDetails])

        @include('jobs.partials.update-available', ['jobDetails' => $job->jobDetails])

        @include('job-extras.create-job-extra')
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

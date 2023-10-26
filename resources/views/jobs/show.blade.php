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
                        icon="fas fa-sort"
                        :data="$job->isInternal() ? 'Inventory Replenishment' : 'Customer Order'"
                        label="Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-spinner"
                        :data="$job->jobCompletionRate"
                        label="Progress"
                    />
                </div>
                @if (!$job->isCompleted())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-info"
                            :data="$job->forecastedCompletionRate == $job->jobCompletionRate ? ' On Schedule' : ($job->forecastedCompletionRate < $job->jobCompletionRate ? 'Ahead of Schedule' : 'Behind Schedule')"
                            label="Schedule"
                        />
                    </div>
                @endif

                @if (!$job->isInternal())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-user"
                            :data="$job->customer->company_name ?? 'N/A'"
                            label="Customer"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-warehouse"
                        :data="$job->factory->name"
                        label="Factory"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$job->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$job->due_date->toFormattedDateString()"
                        label="Due Date"
                    />
                </div>
                @foreach ($job->customFieldValues as $field)
                    <div class="column is-6">
                        <x-common.show-data-section
                            :icon="$field->customField->icon"
                            :data="$field->value"
                            :label="$field->customField->label"
                        />
                    </div>
                @endforeach
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
                @elseif(!$job->isCompleted())
                    @can('Update Wip Job')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="button"
                                mode="button"
                                @click="$dispatch('open-update-wip-modal')"
                                icon="fa fa-plus"
                                label="Update Work In Process"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                    @can('Update Available Job')
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
                @if ($job->isCompleted() && !$job->isClosed())
                    <x-common.dropdown-item>
                        <x-common.transaction-button
                            :route="route('jobs.close', $job->id)"
                            action="close"
                            intention="close this job"
                            icon="fas fa-ban"
                            label="Close"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endif
                @if ($job->isApproved() && !$job->isClosed())
                    @canany(['Add Extra Job', 'Subtract Extra Job'])
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
                    @endcanany
                @endif
                @if ($job->isClosed())
                    @can('Create Sale')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="a"
                                href="{{ route('jobs.convert_to_sale', $job->id) }}"
                                mode="button"
                                icon="fas fa-cash-register"
                                label="Issue Invoice"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
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
            @if ($job->isClosed())
                <x-common.success-message message="The Job is completed and closed." />
            @elseif (!$job->isApproved())
                <x-common.fail-message message="This Job has not been approved yet." />
            @elseif (!$job->isStarted())
                <x-common.fail-message message="Job has not been Started yet." />
            @elseif (!$job->isCompleted())
                <x-common.success-message message="Job Progress: {{ $job->jobCompletionRate }}%" />
            @else
                <x-common.success-message message="Job is completed successfully." />
            @endif
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Extra Materials" />
        <x-content.footer>
            <x-common.fail-message :message="session('jobExtraFailedMessage')" />
            <x-common.success-message :message="session('jobExtraSuccessMessage')" />
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
                                @if ($jobExtra->isTypeInput() && !$jobExtra->isSubtracted())
                                    @can('Subtract Extra Job')
                                        <x-common.transaction-button
                                            :route="route('job-extras.subtract', $jobExtra->id)"
                                            action="subtract"
                                            intention="subtract this Job Materials"
                                            icon="fas fa-minus-circle"
                                            class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
                                        />
                                    @endcan
                                @endif
                                @if (!$jobExtra->isTypeInput() && !$jobExtra->isAdded())
                                    @can('Add Extra Job')
                                        <x-common.transaction-button
                                            :route="route('job-extras.add', $jobExtra->id)"
                                            action="add"
                                            intention="add this Job Materials"
                                            icon="fas fa-plus-circle"
                                            class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
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

    @if ($job->isApproved() && !$job->isCompleted())
        @can('Update Wip Job')
            @include('jobs.partials.update-wip', ['jobDetails' => $job->jobDetails])
        @endcan

        @can('Update Available Job')
            @include('jobs.partials.update-available', ['jobDetails' => $job->jobDetails])
        @endcan
    @endif

    @if ($job->isApproved() && !$job->isClosed())
        @canany(['Add Extra Job', 'Subtract Extra Job'])
            @include('job-extras.create')
        @endcanany
    @endif


@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

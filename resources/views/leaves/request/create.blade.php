@extends('layouts.app')

@section('title', 'Create Leave')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Create New Leave" />
        <form
            id="formOne"
            action="{{ route('leaves.request.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="leave_category_id">
                                Category <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="leave_category_id"
                                    name="leave_category_id"
                                >
                                    @foreach ($leaveCategories as $leaveCategory)
                                        <option value="{{ $leaveCategory->id }}">{{ $leaveCategory->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fa-solid fa-umbrella-beach"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="leave_category_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="starting_period">
                                Starting Period <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="starting_period"
                                    name="starting_period"
                                    type="datetime-local"
                                    value="{{ old('starting_period') }}"
                                    placeholder="mm/dd/yyyy"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="starting_period" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="ending_period">
                                Ending Period <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="ending_period"
                                    name="ending_period"
                                    type="datetime-local"
                                    value="{{ old('ending_period') }}"
                                    placeholder="mm/dd/yyyy"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="ending_period" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="time_off_amount">
                                Time Off {{ userCompany()->paid_time_off_type }} <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="time_off_amount"
                                    name="time_off_amount"
                                    type="number"
                                    placeholder="Time Off Amount"
                                    value="{{ old('time_off_amount') }}"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="time_off_amount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="is_paid_time_off">
                                Is Paid Time Off <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control>
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        id="is_paid_time_off"
                                        name="is_paid_time_off"
                                        type="radio"
                                        value="1"
                                        class="mt-3"
                                        @checked(old('is_paid_time_off'))
                                    >
                                    Yes
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        type="radio"
                                        id="is_paid_time_off"
                                        name="is_paid_time_off"
                                        value="0"
                                        @checked(!old('is_paid_time_off'))
                                    >
                                    No
                                </label>
                                <x-common.validation-error property="is_paid_time_off" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="summernote"
                                    placeholder="Description or note to be taken"
                                >
                                    {{ old('description') ?? '' }}
                                </x-forms.textarea>
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

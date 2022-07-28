@extends('layouts.app')

@section('title', 'Edit Leave')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Leave" />
        <form
            id="formOne"
            action="{{ route('leaves.update', $leaf->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="employee_id">
                                Employee Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="employee_id"
                                    name="employee_id"
                                >
                                    <option value="{{ $leaf->employee->id }}">{{ $leaf->employee->user->name }}</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="employee_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
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
                                        <option
                                            value="{{ $leaveCategory->id }}"
                                            @selected($leaf->leave_category_id == $leaveCategory->id)
                                        >{{ $leaveCategory->name }}</option>
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
                                    placeholder="mm/dd/yyyy"
                                    value="{{ $leaf->starting_period->toDateTimeLocalString() }}"
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
                                    placeholder="mm/dd/yyyy"
                                    value="{{ $leaf->ending_period->toDateTimeLocalString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="ending_period" />
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

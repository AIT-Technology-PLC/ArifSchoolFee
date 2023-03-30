@extends('layouts.app')

@section('title', 'Create New Leave')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Leave" />
        <form
            id="formOne"
            action="{{ route('leaves.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="leaveMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('leave.*')) }})"
            >
                <template
                    x-for="(leave, index) in leaves"
                    x-bind:key="index"
                >
                    <div class="mx-3">
                        <x-forms.field class="has-addons mb-0 mt-5">
                            <x-forms.control>
                                <span
                                    class="tag bg-green has-text-white is-medium is-radiusless"
                                    x-text="`Item ${index + 1}`"
                                ></span>
                            </x-forms.control>
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    mode="tag"
                                    type="button"
                                    class="bg-lightgreen has-text-white is-medium is-radiusless"
                                    x-on:click="remove(index)"
                                >
                                    <x-common.icon
                                        name="fas fa-times-circle"
                                        class="text-green"
                                    />
                                </x-common.button>
                            </x-forms.control>
                        </x-forms.field>
                        <div class="box has-background-white-bis radius-top-0">
                            <div class="columns is-marginless is-multiline">
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`leave[${index}][employee_id]`">
                                            Employee Name <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                x-bind:id="`leave[${index}][employee_id]`"
                                                x-bind:name="`leave[${index}][employee_id]`"
                                                x-model="leave.employee_id"
                                            >
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->employee->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-user"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`leave.${index}.employee_id`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`leave[${index}][leave_category_id]`">
                                            Category <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                x-bind:id="`leave[${index}][leave_category_id]`"
                                                x-bind:name="`leave[${index}][leave_category_id]`"
                                                x-model="leave.leave_category_id"
                                            >
                                                @foreach ($leaveCategories as $leaveCategory)
                                                    <option value="{{ $leaveCategory->id }}">{{ $leaveCategory->name }}</option>
                                                @endforeach
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fa-solid fa-umbrella-beach"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`leave.${index}.leave_category_id`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`leave[${index}][starting_period]`">
                                            Starting Period <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                x-bind:id="`leave[${index}][starting_period]`"
                                                x-bind:name="`leave[${index}][starting_period]`"
                                                x-model="leave.starting_period"
                                                type="datetime-local"
                                                placeholder="mm/dd/yyyy"
                                            />
                                            <x-common.icon
                                                name="fas fa-calendar-alt"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`leave.${index}.starting_period`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`leave[${index}][ending_period]`">
                                            Ending Period <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                x-bind:id="`leave[${index}][ending_period]`"
                                                x-bind:name="`leave[${index}][ending_period]`"
                                                x-model="leave.ending_period"
                                                type="datetime-local"
                                                placeholder="mm/dd/yyyy"
                                            />
                                            <x-common.icon
                                                name="fas fa-calendar-alt"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`leave.${index}.ending_period`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`leave[${index}][time_off_amount]`">
                                            Time Off {{ userCompany()->paid_time_off_type }} <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                x-bind:id="`leave[${index}][time_off_amount]`"
                                                x-bind:name="`leave[${index}][time_off_amount]`"
                                                x-model="leave.time_off_amount"
                                                type="number"
                                                placeholder="Time Off Amount"
                                            />
                                            <x-common.icon
                                                name="fas fa-th"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`leave.${index}.time_off_amount`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`leave[${index}][is_paid_time_off]`">
                                            Is Paid Time Off <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control>
                                            <label class="radio has-text-grey has-text-weight-normal">
                                                <input
                                                    x-bind:id="`leave[${index}][is_paid_time_off]`"
                                                    x-bind:name="`leave[${index}][is_paid_time_off]`"
                                                    x-model="leave.is_paid_time_off"
                                                    type="radio"
                                                    value="1"
                                                    class="mt-3"
                                                >
                                                Yes
                                            </label>
                                            <label class="radio has-text-grey has-text-weight-normal mt-2">
                                                <input
                                                    type="radio"
                                                    x-bind:id="`leave[${index}][is_paid_time_off]`"
                                                    x-bind:name="`leave[${index}][is_paid_time_off]`"
                                                    x-model="leave.is_paid_time_off"
                                                    value="0"
                                                >
                                                No
                                            </label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`leave.${index}.is_paid_time_off`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`leave[${index}][description]`">
                                            Description <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.textarea
                                                x-bind:id="`leave[${index}][description]`"
                                                x-bind:name="`leave[${index}][description]`"
                                                x-model="leave.description"
                                                class="textarea pl-6"
                                                placeholder="Description or note to be taken"
                                            >
                                            </x-forms.textarea>
                                            <x-common.icon
                                                name="fas fa-edit"
                                                class="is-large is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`leave.${index}.description`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <x-common.button
                    tag="button"
                    type="button"
                    mode="button"
                    label="Add More Item"
                    class="bg-purple has-text-white is-small ml-3 mt-6"
                    x-on:click="add"
                />
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("leaveMasterDetailForm", ({
                leave
            }) => ({
                leaves: [],

                async init() {
                    if (leave) {
                        this.leaves = leave;
                        return;
                    }
                    this.add();
                },

                add() {
                    this.leaves.push({});
                },

                async remove(index) {
                    if (this.leaves.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.leaves.splice(index, 1));

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

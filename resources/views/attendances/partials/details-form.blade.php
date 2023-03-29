<x-content.main
    x-data="attendanceMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('attendance.*')) }})"
>
    <template
        x-for="(attendance, index) in attendances"
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
                            <x-forms.label x-bind:for="`attendance[${index}][employee_id]`">
                                Employee Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:id="`attendance[${index}][employee_id]`"
                                    x-bind:name="`attendance[${index}][employee_id]`"
                                    x-model="attendance.employee_id"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Select Employee
                                    </option>

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
                                    x-text="$store.errors.getErrors(`attendance.${index}.employee_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`attendance[${index}][days]`">
                            Days Absent <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`attendance[${index}][days]`"
                                    x-bind:name="`attendance[${index}][days]`"
                                    x-model="attendance.days"
                                    type="number"
                                    placeholder="Days Absent"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`attendance.${index}.days`)"
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


@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("attendanceMasterDetailForm", ({
                attendance
            }) => ({
                attendances: [],

                async init() {
                    if (attendance) {
                        this.attendances = attendance;
                        return;
                    }

                },

                add() {
                    this.attendances.push({});
                },

                async remove(index) {
                    if (this.attendances.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.attendances.splice(index, 1));

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

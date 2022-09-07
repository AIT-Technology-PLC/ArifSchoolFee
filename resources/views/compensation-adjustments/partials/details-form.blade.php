<x-content.main
    x-data="compensationAdjustmentMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('compensationAdjustment.*')) }})"
>
    <template
        x-for="(compensationAdjustment, index) in compensationAdjustments"
        x-bind:key="index"
    >
        <div class="mx-3">
            <x-forms.field class="has-addons mb-0 mt-5">
                <x-forms.control>
                    <span
                        class="tag bg-green has-text-white is-medium is-radiusless"
                        x-text="`Employee ${index + 1}`"
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
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`compensationAdjustment[${index}][employee_id]`">
                                Employee Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:class="`employee-list`"
                                    x-bind:id="`compensationAdjustment[${index}][employee_id]`"
                                    x-bind:name="`compensationAdjustment[${index}][employee_id]`"
                                    x-model="compensationAdjustment.employee_id"
                                    x-init="select2(index)"
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
                                    x-text="$store.errors.getErrors(`compensationAdjustment.${index}.employee_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12 p-0">
                        <div class="box is-shadowless has-background-white-bis p-0">
                            <template
                                x-for="(compensationAdjustmentDetail, compensationAdjustmentDetailIndex) in compensationAdjustment.employeeAdjustments"
                                x-bind:key="compensationAdjustmentDetailIndex"
                            >
                                <div class="mx-3">
                                    <x-forms.field class="has-addons mb-0 mt-5">
                                        <x-forms.control>
                                            <span
                                                class="tag bg-green has-text-white is-medium is-radiusless"
                                                x-text="`Adjustment ${compensationAdjustmentDetailIndex + 1}`"
                                            ></span>
                                        </x-forms.control>
                                        <x-forms.control>
                                            <x-common.button
                                                tag="button"
                                                mode="tag"
                                                type="button"
                                                class="bg-lightgreen has-text-white is-medium is-radiusless"
                                                x-on:click="removeEmployeeAdjustments(index,compensationAdjustmentDetailIndex)"
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
                                                    <x-forms.label x-bind:for="`compensationAdjustment[${index}][employeeAdjustments][${compensationAdjustmentDetailIndex}][compensation_id]`">
                                                        Compensation <sup class="has-text-danger">*</sup>
                                                    </x-forms.label>
                                                    <x-forms.control class="has-icons-left">
                                                        <x-forms.select
                                                            class="is-fullwidth"
                                                            x-bind:id="`compensationAdjustment[${index}][employeeAdjustments][${compensationAdjustmentDetailIndex}][compensation_id]`"
                                                            x-bind:name="`compensationAdjustment[${index}][employeeAdjustments][${compensationAdjustmentDetailIndex}][compensation_id]`"
                                                            x-model="compensationAdjustmentDetail.compensation_id"
                                                        >
                                                            @foreach ($compensations as $compensation)
                                                                <option value="{{ $compensation->id }}">{{ $compensation->name }}</option>
                                                            @endforeach
                                                        </x-forms.select>
                                                        <x-common.icon
                                                            name="fa-solid fa-circle-dollar-to-slot"
                                                            class="is-small is-left"
                                                        />
                                                        <span
                                                            class="help has-text-danger"
                                                            x-text="$store.errors.getErrors(`compensationAdjustment.${index}.employeeAdjustments.${compensationAdjustmentDetailIndex}.compensation_id`)"
                                                        ></span>
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                            <div class="column is-6">
                                                <x-forms.field>
                                                    <x-forms.label x-bind:for="`compensationAdjustment[${index}][employeeAdjustments][${compensationAdjustmentDetailIndex}][amount]`">
                                                        Amount <sup class="has-text-danger">*</sup>
                                                    </x-forms.label>
                                                    <x-forms.control class="has-icons-left">
                                                        <x-forms.input
                                                            type="number"
                                                            x-bind:id="`compensationAdjustment[${index}][employeeAdjustments][${compensationAdjustmentDetailIndex}][amount]`"
                                                            x-bind:name="`compensationAdjustment[${index}][employeeAdjustments][${compensationAdjustmentDetailIndex}][amount]`"
                                                            x-model="compensationAdjustmentDetail.amount"
                                                        />
                                                        <x-common.icon
                                                            name="fas fa-money-bill"
                                                            class="is-small is-left"
                                                        />
                                                        <span
                                                            class="help has-text-danger"
                                                            x-text="$store.errors.getErrors(`compensationAdjustment.${index}.employeeAdjustments.${compensationAdjustmentDetailIndex}.amount`)"
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
                                label="Add More Adjustment"
                                class="bg-green has-text-white is-small ml-3 mt-6"
                                x-on:click="addEmployeeAdjustments(index)"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <x-common.button
        tag="button"
        type="button"
        mode="button"
        label="Add More Employee"
        class="bg-purple has-text-white is-small ml-3 mt-6"
        x-on:click="add"
    />
</x-content.main>

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("compensationAdjustmentMasterDetailForm", ({
                compensationAdjustment
            }) => ({
                compensationAdjustments: [],

                async init() {
                    if (compensationAdjustment) {
                        Promise.resolve(this.compensationAdjustments = compensationAdjustment);

                        await Promise.resolve($(".employee-list").trigger("change"));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.compensationAdjustments.push({
                        employeeAdjustments: []
                    });
                },
                addEmployeeAdjustments(index) {
                    this.compensationAdjustments[index].employeeAdjustments.push({});
                },
                async remove(index) {
                    if (this.compensationAdjustments.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.compensationAdjustments.splice(index, 1));

                    await Promise.resolve($(".employee-list").trigger("change"));

                    Pace.restart();
                },
                async removeEmployeeAdjustments(index, employeeAdjustmentIndex) {
                    await Promise.resolve(this.compensationAdjustments[index].employeeAdjustments.splice(employeeAdjustmentIndex, 1));
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event) => {
                        this.compensationAdjustments[index].employee_id = event.target.value;
                    });
                }
            }));
        });
    </script>
@endpush

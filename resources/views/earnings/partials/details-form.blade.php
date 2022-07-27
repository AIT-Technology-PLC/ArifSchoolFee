<x-content.main
    x-data="earningMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('earning.*')) }})"
>
    <template
        x-for="(earning, index) in earnings"
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
                            <x-forms.label x-bind:for="`earning[${index}][employee_id]`">
                                Employee Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:class="`employee-list`"
                                    x-bind:id="`earning[${index}][employee_id]`"
                                    x-bind:name="`earning[${index}][employee_id]`"
                                    x-model="earning.employee_id"
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
                                    x-text="$store.errors.getErrors(`earning.${index}.employee_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12 p-0">
                        <div class="box is-shadowless has-background-white-bis p-0">
                            <template
                                x-for="(earningDetail, earningDetailIndex) in earning.employeeEarnings"
                                x-bind:key="earningDetailIndex"
                            >
                                <div class="mx-3">
                                    <x-forms.field class="has-addons mb-0 mt-5">
                                        <x-forms.control>
                                            <span
                                                class="tag bg-green has-text-white is-medium is-radiusless"
                                                x-text="`Earning ${earningDetailIndex + 1}`"
                                            ></span>
                                        </x-forms.control>
                                        <x-forms.control>
                                            <x-common.button
                                                tag="button"
                                                mode="tag"
                                                type="button"
                                                class="bg-lightgreen has-text-white is-medium is-radiusless"
                                                x-on:click="removeEmployeeEarnings(index,earningDetailIndex)"
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
                                                    <x-forms.label x-bind:for="`earning[${index}][employeeEarnings][${earningDetailIndex}][earning_category_id]`">
                                                        Category <sup class="has-text-danger">*</sup>
                                                    </x-forms.label>
                                                    <x-forms.control class="has-icons-left">
                                                        <x-forms.select
                                                            class="is-fullwidth"
                                                            x-bind:id="`earning[${index}][employeeEarnings][${earningDetailIndex}][earning_category_id]`"
                                                            x-bind:name="`earning[${index}][employeeEarnings][${earningDetailIndex}][earning_category_id]`"
                                                            x-model="earningDetail.earning_category_id"
                                                        >
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
                                                        </x-forms.select>
                                                        <x-common.icon
                                                            name="fas fa-coins"
                                                            class="is-small is-left"
                                                        />
                                                        <span
                                                            class="help has-text-danger"
                                                            x-text="$store.errors.getErrors(`earning.${index}.employeeEarnings.${earningDetailIndex}.earning_category_id`)"
                                                        ></span>
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                            <div class="column is-6">
                                                <x-forms.field>
                                                    <x-forms.label x-bind:for="`earning[${index}][employeeEarnings][${earningDetailIndex}][amount]`">
                                                        Amount <sup class="has-text-danger">*</sup>
                                                    </x-forms.label>
                                                    <x-forms.control class="has-icons-left">
                                                        <x-forms.input
                                                            type="number"
                                                            x-bind:id="`earning[${index}][employeeEarnings][${earningDetailIndex}][amount]`"
                                                            x-bind:name="`earning[${index}][employeeEarnings][${earningDetailIndex}][amount]`"
                                                            x-model="earningDetail.amount"
                                                        />
                                                        <x-common.icon
                                                            name="fas fa-money-bill"
                                                            class="is-small is-left"
                                                        />
                                                        <span
                                                            class="help has-text-danger"
                                                            x-text="$store.errors.getErrors(`earning.${index}.employeeEarnings.${earningDetailIndex}.amount`)"
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
                                label="Add More Earning"
                                class="bg-green has-text-white is-small ml-3 mt-6"
                                x-on:click="addEmployeeEarnings(index)"
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
            Alpine.data("earningMasterDetailForm", ({
                earning
            }) => ({
                earnings: [],

                async init() {
                    if (earning) {
                        await Promise.resolve(
                            earning.forEach((item, index) => {
                                this.earnings.push({
                                    employee_id: item[0].employee_id,
                                    employeeEarnings: item
                                });
                            })
                        );

                        await Promise.resolve($(".employee-list").trigger("change"));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.earnings.push({
                        employeeEarnings: []
                    });
                },
                addEmployeeEarnings(index) {
                    this.earnings[index].employeeEarnings.push({});
                },
                async remove(index) {
                    if (this.earnings.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.earnings.splice(index, 1));

                    await Promise.resolve($(".employee-list").trigger("change"));

                    Pace.restart();
                },
                async removeEmployeeEarnings(index, employeeEarningIndex) {
                    await Promise.resolve(this.earnings[index].employeeEarnings.splice(employeeEarningIndex, 1));
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event) => {
                        this.earnings[index].employee_id = event.target.value;
                    });
                }
            }));
        });
    </script>
@endpush

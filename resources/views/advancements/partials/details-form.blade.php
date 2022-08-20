<x-content.main
    x-data="advancementMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('advancement.*')) }})"
>
    <template
        x-for="(advancement, index) in advancements"
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
                            <x-forms.label x-bind:for="`advancement[${index}][employee_id]`">
                                Employee Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:class="`employee-list`"
                                    x-bind:id="`advancement[${index}][employee_id]`"
                                    x-bind:name="`advancement[${index}][employee_id]`"
                                    x-model="advancement.employee_id"
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
                                    x-text="$store.errors.getErrors(`advancement.${index}.employee_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`advancement[${index}][job_position]`">
                            Job Position <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    type="text"
                                    x-bind:id="`advancement[${index}][job_position]`"
                                    x-bind:name="`advancement[${index}][job_position]`"
                                    x-model="advancement.job_position"
                                />
                                <x-common.icon
                                    name="fas fa-user-tie"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`advancement.${index}.job_position`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`advancement[${index}][compensation_id]`">
                                Compensation <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:id="`advancement[${index}][compensation_id]`"
                                    x-bind:name="`advancement[${index}][compensation_id]`"
                                    x-model="advancement.compensation_id"
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
                                    x-text="$store.errors.getErrors(`advancement.${index}.compensation_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`advancement[${index}][amount]`">
                            Amount <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`advancement[${index}][amount]`"
                                    x-bind:name="`advancement[${index}][amount]`"
                                    x-model="advancement.amount"
                                    type="number"
                                    placeholder="Amount"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`advancement.${index}.amount`)"
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
            Alpine.data("advancementMasterDetailForm", ({
                advancement
            }) => ({
                advancements: [],

                async init() {
                    if (advancement) {
                        await Promise.resolve(this.advancements = advancement);

                        await Promise.resolve($(".employee-list").trigger("change"));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.advancements.push({});
                },
                async remove(index) {
                    if (this.advancements.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.advancements.splice(index, 1));

                    await Promise.resolve($(".employee-list").trigger("change"));

                    Pace.restart();
                },
                select2(index) {
                    let select2 = initializeSelect2(this.$el);

                    select2.on("change", (event) => {
                        this.advancements[index].employee_id = event.target.value;
                    });
                }
            }));
        });
    </script>
@endpush

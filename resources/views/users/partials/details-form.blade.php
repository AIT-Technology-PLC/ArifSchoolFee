<x-content.main
    x-data="employeeCompensationMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('employeeCompensation.*')) }})"
    class="is-shadowless is-borderless p-0"
>
    <template
        x-for="(employeeCompensation, index) in employeeCompensations"
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
                            <x-forms.label x-bind:for="`employeeCompensation[${index}][compensation_id]`">
                                Compensation <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:id="`employeeCompensation[${index}][compensation_id]`"
                                    x-bind:name="`employeeCompensation[${index}][compensation_id]`"
                                    x-model="employeeCompensation.compensation_id"
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
                                    x-text="$store.errors.getErrors(`employeeCompensation.${index}.compensation_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`employeeCompensation[${index}][amount]`">
                            Amount <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`employeeCompensation[${index}][amount]`"
                                    x-bind:name="`employeeCompensation[${index}][amount]`"
                                    x-model="employeeCompensation.amount"
                                    type="number"
                                    placeholder="Amount"
                                />
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`employeeCompensation.${index}.amount`)"
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
            Alpine.data("employeeCompensationMasterDetailForm", ({
                employeeCompensation
            }) => ({
                employeeCompensations: [],

                init() {
                    if (employeeCompensation) {
                        this.employeeCompensations = employeeCompensation;
                        return;
                    }

                    this.add();
                },

                add() {
                    this.employeeCompensations.push({});
                },

                async remove(index) {
                    if (this.employeeCompensations.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.employeeCompensations.splice(index, 1));

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

<x-content.main
    x-data="employeeTransferMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('employeeTransfer.*')) }})"
>
    <template
        x-for="(employeeTransfer, index) in employeeTransfers"
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
                            <x-forms.label x-bind:for="`employeeTransfer[${index}][employee_id]`">
                                Employee Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:id="`employeeTransfer[${index}][employee_id]`"
                                    x-bind:name="`employeeTransfer[${index}][employee_id]`"
                                    x-model="employeeTransfer.employee_id"
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
                                    x-text="$store.errors.getErrors(`gdn.${index}.employee_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`employeeTransfer[${index}][warehouse_id]`">
                                Transfer To <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:id="`employeeTransfer[${index}][warehouse_id]`"
                                    x-bind:name="`employeeTransfer[${index}][warehouse_id]`"
                                    x-model="employeeTransfer.warehouse_id"
                                >
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-warehouse"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`gdn.${index}.warehouse_id`)"
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
            Alpine.data("employeeTransferMasterDetailForm", ({
                employeeTransfer
            }) => ({
                employeeTransfers: [],

                init() {
                    if (employeeTransfer) {
                        this.employeeTransfers = employeeTransfer;
                        return;
                    }

                    this.add();
                },
                add() {
                    this.employeeTransfers.push({});
                },
                remove(index) {
                    if (this.employeeTransfers.length <= 0) {
                        return;
                    }

                    this.employeeTransfers.splice(index, 1);

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

<div
    x-data="padStatusMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('status.*')) }})"
>
    <template
        x-for="(status, index) in statuses"
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
                        <x-forms.label for="`status[${index}][name]`">
                            Status <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    x-bind:name="`status[${index}][name]`"
                                    x-bind:id="`status[${index}][name]`"
                                    x-model="status.name"
                                />
                                <x-common.icon
                                    name="fas fa-info"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                            <span
                                class="help has-text-danger"
                                x-text="$store.errors.getErrors(`status.${index}.name`)"
                            ></span>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Background & Text Color <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`status[${index}][bg_color]`"
                                    x-bind:name="`status[${index}][bg_color]`"
                                    placeholder="Background Color"
                                    type="color"
                                    x-model="status.bg_color"
                                />
                                <x-common.icon
                                    name="fas fa-palette"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`status.${index}.bg_color`)"
                                ></span>
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`status[${index}][text_color]`"
                                    x-bind:name="`status[${index}][text_color]`"
                                    placeholder="Text Color"
                                    type="color"
                                    x-model="status.text_color"
                                />
                                <x-common.icon
                                    name="fas fa-palette"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`status.${index}.text_color`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label>
                                Active <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        x-bind:name="`status[${index}][is_active]`"
                                        x-bind:id="`status[${index}][is_active]`"
                                        value="1"
                                        x-model="status.is_active"
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        x-bind:name="`status[${index}][is_active]`"
                                        x-bind:id="`status[${index}][is_active]`"
                                        value="0"
                                        x-model="status.is_active"
                                    />
                                    No
                                </x-forms.label>
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`status.${index}.is_active`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label>
                                Editable <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        x-bind:name="`status[${index}][is_editable]`"
                                        x-bind:id="`status[${index}][is_editable]`"
                                        value="1"
                                        x-model="status.is_editable"
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        x-bind:name="`status[${index}][is_editable]`"
                                        x-bind:id="`status[${index}][is_editable]`"
                                        value="0"
                                        x-model="status.is_editable"
                                    />
                                    No
                                </x-forms.label>
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`status.${index}.is_editable`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label>
                                Deletable <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        x-bind:name="`status[${index}][is_deletable]`"
                                        x-bind:id="`status[${index}][is_deletable]`"
                                        value="1"
                                        x-model="status.is_deletable"
                                    />
                                    Yes
                                </x-forms.label>
                                <x-forms.label class="radio is-inline">
                                    <input
                                        type="radio"
                                        x-bind:name="`status[${index}][is_deletable]`"
                                        x-bind:id="`status[${index}][is_deletable]`"
                                        value="0"
                                        x-model="status.is_deletable"
                                    />
                                    No
                                </x-forms.label>
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`status.${index}.is_deletable`)"
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
        label="Add Status"
        icon="fas fa-plus-circle"
        class="bg-purple has-text-white is-small ml-3 mt-6"
        x-on:click="add"
    />
</div>

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("padStatusMasterDetailForm", ({
                status
            }) => ({
                statuses: [],

                init() {
                    if (status) {
                        this.statuses = status;
                        return;
                    }
                },
                add() {
                    this.statuses.push({});
                },
                remove(index) {
                    this.statuses.splice(index, 1);
                },
            }));
        });
    </script>
@endpush

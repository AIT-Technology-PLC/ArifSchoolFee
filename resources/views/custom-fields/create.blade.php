@extends('layouts.app')

@section('title', 'Create New Custom Field')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Custom Field" />
        <form
            id="formOne"
            action="{{ route('custom-fields.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="customFieldMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('customField.*')) }})"
            >
                <template
                    x-for="(customField, index) in customFields"
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
                                <div class="column is-4">
                                    <x-forms.label x-bind:for="`customField[${index}][label]`">
                                        Label <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`customField[${index}][label]`"
                                                x-bind:name="`customField[${index}][label]`"
                                                x-model="customField.label"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-table"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customField.${index}.label`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.label x-bind:for="`customField[${index}][tag]`">
                                        Tag <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field x-bind:class="{ 'has-addons': isTagInput(customField.tag) }">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:name="`customField[${index}][tag]`"
                                                x-bind:id="`customField[${index}][tag]`"
                                                x-model="customField.tag"
                                            />
                                            <x-common.icon
                                                name="fas fa-code"
                                                class="is-large is-left"
                                            />
                                        </x-forms.control>
                                        <x-forms.control
                                            x-show="isTagInput(customField.tag)"
                                            class="has-icons-left"
                                        >
                                            <x-forms.input
                                                type="text"
                                                placeholder="Input Type (e.g. text, number ...)"
                                                x-bind:name="`customField[${index}][tag_type]`"
                                                x-bind:id="`customField[${index}][tag_type]`"
                                                x-model="customField.tag_type"
                                            />
                                            <x-common.icon
                                                name="fas fa-code"
                                                class="is-large is-left"
                                            />
                                        </x-forms.control>
                                        <span
                                            class="help has-text-danger"
                                            x-text="errors[`customField.${index}.tag`]"
                                        ></span>
                                        <span
                                            class="help has-text-danger"
                                            x-text="errors[`customField.${index}.tag_type`]"
                                        ></span>
                                    </x-forms.field>
                                </div>
                                <div
                                    class="column is-4"
                                    x-show="canHaveOptions(index)"
                                >
                                    <x-forms.label x-bind:for="`customField[${index}][options]`">
                                        Options <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`customField[${index}][options]`"
                                                x-bind:name="`customField[${index}][options]`"
                                                x-model="customField.options"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-list"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customField.${index}.options`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.label x-bind:for="`customField[${index}][model_type]`">
                                        Model Type <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`customField[${index}][model_type]`"
                                                x-bind:name="`customField[${index}][model_type]`"
                                                x-model="customField.model_type"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-diagram-project"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customField.${index}.model_type`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.label x-bind:for="`customField[${index}][order]`">
                                        Order <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`customField[${index}][order]`"
                                                x-bind:name="`customField[${index}][order]`"
                                                x-model="customField.order"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-sort"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customField.${index}.order`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.label x-bind:for="`customField[${index}][icon]`">
                                        Icon <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`customField[${index}][icon]`"
                                                x-bind:name="`customField[${index}][icon]`"
                                                x-model="customField.icon"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-icons"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customField.${index}.icon`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.label x-bind:for="`customField[${index}][placeholder]`">
                                        Placeholder <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`customField[${index}][placeholder]`"
                                                x-bind:name="`customField[${index}][placeholder]`"
                                                x-model="customField.placeholder"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-text-width"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customField.${index}.placeholder`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.label x-bind:for="`customField[${index}][default_value]`">
                                        Default Value <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`customField[${index}][default_value]`"
                                                x-bind:name="`customField[${index}][default_value]`"
                                                x-model="customField.default_value"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-info"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customField.${index}.default_value`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.label x-bind:for="`customField[${index}][column_size]`">
                                        Column Size <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`customField[${index}][column_size]`"
                                                x-bind:name="`customField[${index}][column_size]`"
                                                x-model="customField.column_size"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-ruler-horizontal"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customField.${index}.column_size`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.field>
                                        <x-forms.label>
                                            Enabled <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    x-bind:name="`customField[${index}][is_active]`"
                                                    x-bind:id="`customField[${index}][is_active]`"
                                                    value="1"
                                                    x-model="customField.is_active"
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    x-bind:name="`customField[${index}][is_active]`"
                                                    x-bind:id="`customField[${index}][is_active]`"
                                                    value="0"
                                                    x-model="customField.is_active"
                                                />
                                                No
                                            </x-forms.label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`customField.${index}.is_active`]"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.field>
                                        <x-forms.label>
                                            Master Field <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    x-bind:name="`customField[${index}][is_master]`"
                                                    x-bind:id="`customField[${index}][is_master]`"
                                                    value="1"
                                                    x-model="customField.is_master"
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    x-bind:name="`customField[${index}][is_master]`"
                                                    x-bind:id="`customField[${index}][is_master]`"
                                                    value="0"
                                                    x-model="customField.is_master"
                                                />
                                                No
                                            </x-forms.label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`customField.${index}.is_master`]"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.field>
                                        <x-forms.label>
                                            Required <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    x-bind:name="`customField[${index}][is_required]`"
                                                    x-bind:id="`customField[${index}][is_required]`"
                                                    value="1"
                                                    x-model="customField.is_required"
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    x-bind:name="`customField[${index}][is_required]`"
                                                    x-bind:id="`customField[${index}][is_required]`"
                                                    value="0"
                                                    x-model="customField.is_required"
                                                />
                                                No
                                            </x-forms.label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`customField.${index}.is_required`]"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.field>
                                        <x-forms.label>
                                            Column Visibility <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    x-bind:name="`customField[${index}][is_visible]`"
                                                    x-bind:id="`customField[${index}][is_visible]`"
                                                    value="1"
                                                    x-model="customField.is_visible"
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    x-bind:name="`customField[${index}][is_visible]`"
                                                    x-bind:id="`customField[${index}][is_visible]`"
                                                    value="0"
                                                    x-model="customField.is_visible"
                                                />
                                                No
                                            </x-forms.label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`customField.${index}.is_visible`]"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-4">
                                    <x-forms.field>
                                        <x-forms.label>
                                            Printable <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    x-bind:name="`customField[${index}][is_printable]`"
                                                    x-bind:id="`customField[${index}][is_printable]`"
                                                    value="1"
                                                    x-model="customField.is_printable"
                                                />
                                                Yes
                                            </x-forms.label>
                                            <x-forms.label class="radio is-inline">
                                                <input
                                                    type="radio"
                                                    x-bind:name="`customField[${index}][is_printable]`"
                                                    x-bind:id="`customField[${index}][is_printable]`"
                                                    value="0"
                                                    x-model="customField.is_printable"
                                                />
                                                No
                                            </x-forms.label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="errors[`customField.${index}.is_printable`]"
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
            Alpine.data("customFieldMasterDetailForm", ({
                customField
            }) => ({
                customFields: [],

                async init() {
                    if (customField) {
                        this.customFields = customField;

                        return;
                    }

                    this.add();
                },

                add() {
                    this.customFields.push({});
                },

                async remove(index) {
                    if (this.customFields.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.customFields.splice(index, 1));

                    Pace.restart();
                },
                isTagInput(tagName) {
                    if (!tagName) {
                        return false;
                    }

                    return tagName.toLowerCase() === "input";
                },
                canHaveOptions(index) {
                    return this.customFields[index]?.tag?.toLowerCase() === "select" || this.customFields[index]?.tag_type?.toLowerCase() === "radio";
                },
            }));
        });
    </script>
@endpush

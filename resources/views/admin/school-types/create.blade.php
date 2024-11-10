@extends('layouts.app')

@section('title', 'Create Type')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        New Type
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.school-types.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="schoolTypeMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('schoolType.*')) }})"
            >
                <template
                    x-for="(schoolType, index) in schoolTypes"
                    x-bind:key="index"
                >
                    <div class="mx-3">
                        <x-forms.field class="has-addons mb-0 mt-5">
                            <x-forms.control>
                                <span
                                    class="tag bg-blue has-text-white is-medium is-radiusless"
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
                                <div class="column is-12">
                                    <x-forms.label x-bind:for="`schoolType[${index}][name]`">
                                        Name <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`schoolType[${index}][name]`"
                                                x-bind:name="`schoolType[${index}][name]`"
                                                x-model="schoolType.name"
                                                placeholder="Type Name"
                                                oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
                                            />
                                            <x-common.icon
                                                name="fas fa-heading"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`schoolType.${index}.name`)"
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
                    label="Add More"
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
            Alpine.data("schoolTypeMasterDetailForm", ({
                schoolType
            }) => ({
                schoolTypes: [],

                async init() {
                    if (schoolType) {
                        this.schoolTypes = schoolType;

                        return;
                    }

                    this.add();
                },
                add() {
                    this.schoolTypes.push({});
                },
                remove(index) {
                    if (this.schoolTypes.length <= 0) {
                        return;
                    }

                    this.schoolTypes.splice(index, 1);

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Create New Item Type')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Item" />
        <form
            id="formOne"
            action="{{ route('items.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="itemMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('item.*')) }})"
            >
                <template
                    x-for="(item, index) in items"
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
                                    <x-forms.label x-bind:for="`item[${index}][name]`">
                                        Name <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`item[${index}][name]`"
                                                x-bind:name="`item[${index}][name]`"
                                                x-model="item.name"
                                                placeholder="Item Type Name"
                                            />
                                            <x-common.icon
                                                name="fas fa-trademark"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`item.${index}.name`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`item[${index}][description]`">
                                            Description <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.textarea
                                                x-bind:id="`item[${index}][description]`"
                                                x-bind:name="`item[${index}][description]`"
                                                x-model="item.description"
                                                class="textarea pl-6"
                                                placeholder="Description or note about the new Item Type"
                                            >
                                            </x-forms.textarea>
                                            <x-common.icon
                                                name="fas fa-edit"
                                                class="is-large is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`item.${index}.description`)"
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
            Alpine.data("itemMasterDetailForm", ({
                item
            }) => ({
                items: [],

                async init() {
                    if (item) {
                        this.items = item;

                        return;
                    }

                    this.add();
                },
                add() {
                    this.items.push({});
                },
                remove(index) {
                    if (this.items.length <= 0) {
                        return;
                    }

                    this.items.splice(index, 1);

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

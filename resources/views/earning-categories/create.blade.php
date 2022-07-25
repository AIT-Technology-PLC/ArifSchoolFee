@extends('layouts.app')

@section('title', 'Create New Earning Category')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Earning Category" />
        <form
            id="formOne"
            action="{{ route('earning-categories.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="earningCategoryMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('earningCategory.*')) }})"
            >
                <template
                    x-for="(earningCategory, index) in earningCategories"
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
                                    <x-forms.label x-bind:for="`earningCategory[${index}][name]`">
                                        Name <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`earningCategory[${index}][name]`"
                                                x-bind:name="`earningCategory[${index}][name]`"
                                                x-model="earningCategory.name"
                                                placeholder="category name"
                                            />
                                            <x-common.icon
                                                name="fas fa-user"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`earningCategory.${index}.name`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`earningCategory[${index}][type]`">
                                            Type <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left ">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                x-bind:id="`earningCategory[${index}][type]`"
                                                x-bind:name="`earningCategory[${index}][type]`"
                                                x-model="earningCategory.type"
                                            >
                                                <option value="Before Tax">Before Tax</option>
                                                <option value="After Tax">After Tax</option>
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-sort"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`earningCategory.${index}.type`)"
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
            Alpine.data("earningCategoryMasterDetailForm", ({
                earningCategory
            }) => ({
                earningCategories: [],

                async init() {
                    if (earningCategory) {
                        this.earningCategories = earningCategory;

                        return;
                    }

                    this.add();
                },
                add() {
                    this.earningCategories.push({});
                },
                remove(index) {
                    if (this.earningCategories.length <= 0) {
                        return;
                    }

                    this.earningCategories.splice(index, 1);

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

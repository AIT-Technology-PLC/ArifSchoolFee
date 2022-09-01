@extends('layouts.app')

@section('title', 'Create New Expense Category')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Expense Category" />
        <form
            id="formOne"
            action="{{ route('expense-categories.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="expenseCategoryMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('expenseCategory.*')) }})"
            >
                <template
                    x-for="(expenseCategory, index) in expenseCategories"
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
                                <div class="column is-12">
                                    <x-forms.label x-bind:for="`expenseCategory[${index}][name]`">
                                        Name <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`expenseCategory[${index}][name]`"
                                                x-bind:name="`expenseCategory[${index}][name]`"
                                                x-model="expenseCategory.name"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-money-bill-trend-up"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`expenseCategory.${index}.name`)"
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
            Alpine.data("expenseCategoryMasterDetailForm", ({
                expenseCategory
            }) => ({
                expenseCategories: [],

                async init() {
                    if (expenseCategory) {
                        this.expenseCategories = expenseCategory;

                        return;
                    }

                    this.add();
                },

                add() {
                    this.expenseCategories.push({});
                },

                async remove(index) {
                    if (this.expenseCategories.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.expenseCategories.splice(index, 1));

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

<x-content.main
    x-data="expenseMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('expense.*')) }})"
>
    <template
        x-for="(expense, index) in expenses"
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
                            <x-forms.label x-bind:for="`expense[${index}][name]`">
                                Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    class="is-fullwidth"
                                    x-bind:id="`expense[${index}][name]`"
                                    x-bind:name="`expense[${index}][name]`"
                                    x-model="expense.name"
                                    type="text"
                                    placeholder="Name"
                                    list="name"
                                />
                                <datalist id="name" class="is-absolute" style="left: 0;right: 0;">
                                    @foreach ($expenseNames as $expenseName)
                                        <option value="{{ $expenseName }}" />
                                    @endforeach
                                </datalist>
                                <x-common.icon
                                    name="fa-solid fa-money-bill-trend-up"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`expense.${index}.name`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label x-bind:for="`expense[${index}][expense_category_id]`">
                                Category <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    x-bind:id="`expense[${index}][expense_category_id]`"
                                    x-bind:name="`expense[${index}][expense_category_id]`"
                                    x-model="expense.expense_category_id"
                                >
                                    @foreach ($expenseCategories as $expenseCategory)
                                        <option value="{{ $expenseCategory->id }}">{{ $expenseCategory->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fa-solid fa-money-bill-trend-up"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`expense.${index}.expense_category_id`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`expense[${index}][quantity]`">
                            Quantity <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`expense[${index}][quantity]`"
                                    x-bind:name="`expense[${index}][quantity]`"
                                    x-model="expense.quantity"
                                    type="number"
                                    placeholder="Quantity"
                                />
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`expense.${index}.quantity`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`expense[${index}][unit_price]`">
                            Unit Price <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`expense[${index}][unit_price]`"
                                    x-bind:name="`expense[${index}][unit_price]`"
                                    x-model="expense.unit_price"
                                    type="number"
                                    placeholder="Unit Price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`expense.${index}.unit_price`)"
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
            Alpine.data("expenseMasterDetailForm", ({
                expense
            }) => ({
                expenses: [],

                async init() {
                    if (expense) {
                        this.expenses = expense;
                        return;
                    }
                    this.add();
                },

                add() {
                    this.expenses.push({});
                },

                async remove(index) {
                    if (this.expenses.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.expenses.splice(index, 1));

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

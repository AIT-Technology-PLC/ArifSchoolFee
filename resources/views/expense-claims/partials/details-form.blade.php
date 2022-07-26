<x-content.main
    x-data="expenseClaimMasterDetailForm({{ Js::from($data) }})"
    x-init="$store.errors.setErrors({{ Js::from($errors->get('expenseClaim.*')) }})"
>
    <template
        x-for="(expenseClaim, index) in expenseClaims"
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
                        <x-forms.label x-bind:for="`expenseClaim[${index}][item]`">
                            Item <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`expenseClaim[${index}][item]`"
                                    x-bind:name="`expenseClaim[${index}][item]`"
                                    x-model="expenseClaim.item"
                                    type="text"
                                    placeholder="Item"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`expenseClaim.${index}.item`)"
                                ></span>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label x-bind:for="`expenseClaim[${index}][price]`">
                            Price <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    x-bind:id="`expenseClaim[${index}][price]`"
                                    x-bind:name="`expenseClaim[${index}][price]`"
                                    x-model="expenseClaim.price"
                                    type="number"
                                    placeholder="Price"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <span
                                    class="help has-text-danger"
                                    x-text="$store.errors.getErrors(`expenseClaim.${index}.price`)"
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
            Alpine.data("expenseClaimMasterDetailForm", ({
                expenseClaim
            }) => ({
                expenseClaims: [],

                async init() {
                    if (expenseClaim) {
                        this.expenseClaims = expenseClaim;

                        return;
                    }

                    this.add();
                },

                add() {
                    this.expenseClaims.push({});
                },

                async remove(index) {
                    if (this.expenseClaims.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.expenseClaims.splice(index, 1));

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

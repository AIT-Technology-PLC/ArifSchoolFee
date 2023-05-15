@extends('layouts.app')

@section('title', 'Create New Deposit')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Deposit" />
        <form
            id="formOne"
            action="{{ route('customer-deposits.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="customerDepositMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('customerDeposit.*')) }})"
            >
                <template
                    x-for="(customerDeposit, index) in customerDeposits"
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
                                    <x-forms.label x-bind:for="`customerDeposit[${index}][customer_id]`">
                                        Customer <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left select is-fullwidth">
                                            <x-common.customer-list
                                                class="customer-list"
                                                x-bind:id="`customerDeposit[${index}][customer_id]`"
                                                x-bind:name="`customerDeposit[${index}][customer_id]`"
                                                x-model="customerDeposit.customer_id"
                                                x-init="select2(index)"
                                                selected-id=""
                                            />
                                            <x-common.icon
                                                name="fas fa-address-book"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customerDeposit.${index}.customer_id`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`customerDeposit[${index}][issued_on]`">
                                            Issued On <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                type="datetime-local"
                                                x-bind:id="`customerDeposit[${index}][issued_on]`"
                                                x-bind:name="`customerDeposit[${index}][issued_on]`"
                                                x-model="customerDeposit.issued_on"
                                                x-init="customerDeposit.issued_on ?? (customerDeposit.issued_on = '{{ now()->toDateTimeLocalString() }}')"
                                            />
                                            <x-common.icon
                                                name="fas fa-calendar-alt"
                                                class="is-large is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customerDeposit.${index}.issued_on`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`customerDeposit[${index}][deposited_at]`">
                                            Deposited At <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                type="datetime-local"
                                                x-bind:id="`customerDeposit[${index}][deposited_at]`"
                                                x-bind:name="`customerDeposit[${index}][deposited_at]`"
                                                x-model="customerDeposit.deposited_at"
                                            />
                                            <x-common.icon
                                                name="fas fa-calendar-alt"
                                                class="is-large is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customerDeposit.${index}.deposited_at`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.label x-bind:for="`customerDeposit[${index}][amount]`">
                                        Amount <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="number"
                                                x-bind:id="`customerDeposit[${index}][amount]`"
                                                x-bind:name="`customerDeposit[${index}][amount]`"
                                                x-model="customerDeposit.amount"
                                                placeholder="Amount"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-sack-dollar"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customerDeposit.${index}.amount`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`customerDeposit[${index}][bank_name]`">
                                            Bank <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                x-bind:id="`customerDeposit[${index}][bank_name]`"
                                                x-bind:name="`customerDeposit[${index}][bank_name]`"
                                                x-model="customerDeposit.bank_name"
                                            >
                                                <option
                                                    selected
                                                    value=""
                                                > Select Bank </option>
                                                @include('lists.banks')
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-university"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customerDeposit.${index}.bank_name`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.label x-bind:for="`customerDeposit[${index}][reference_number]`">
                                        Reference No <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.field>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`customerDeposit[${index}][reference_number]`"
                                                x-bind:name="`customerDeposit[${index}][reference_number]`"
                                                x-model="customerDeposit.reference_number"
                                                placeholder="Reference No"
                                            />
                                            <x-common.icon
                                                name="fas fa-hashtag"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`customerDeposit.${index}.reference_number`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div
                                    class="column is-6"
                                    x-data="UploadedFileNameHandler"
                                >
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`customerDeposit[${index}][attachment]`">
                                            Attachment <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <div class="file has-name">
                                            <label class="file-label">
                                                <x-forms.input
                                                    class="file-input"
                                                    type="file"
                                                    x-bind:id="`customerDeposit[${index}][attachment]`"
                                                    x-bind:name="`customerDeposit[${index}][attachment]`"
                                                    x-model="customerDeposit.attachment"
                                                    x-model="file"
                                                    x-on:change="getFileName"
                                                />
                                                <span class="file-cta bg-green has-text-white">
                                                    <x-common.icon
                                                        name="fas fa-upload"
                                                        class="file-icon"
                                                    />
                                                    <span class="file-label">
                                                        Upload Attachment
                                                    </span>
                                                </span>
                                                <span
                                                    class="file-name"
                                                    x-text="fileName || customerDeposit.attachment || 'Select File...'"
                                                >
                                                </span>
                                            </label>
                                        </div>
                                        <span
                                            class="help has-text-danger"
                                            x-text="$store.errors.getErrors(`customerDeposit.${index}.attachment`)"
                                        ></span>
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

    @can('Create Customer')
        <x-common.customer-form-modal />
    @endcan
@endsection

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("customerDepositMasterDetailForm", ({
                customerDeposit
            }) => ({
                customerDeposits: [],

                async init() {
                    if (customerDeposit) {
                        await Promise.resolve(this.customerDeposits = customerDeposit);

                        await Promise.resolve($(".customer-list").trigger("change"));

                        return;
                    }

                    this.add();
                },

                add() {
                    this.customerDeposits.push({});
                },

                async remove(index) {
                    if (this.customerDeposits.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.customerDeposits.splice(index, 1));

                    await Promise.resolve($(".customer-list").trigger("change"));

                    Pace.restart();
                },
                select2(index) {
                    let select2 = initSelect2(this.$el, 'Customer');

                    select2.on("change", async (event, customer = null) => {
                        this.customerDeposits[index].customer_id = event.target.value;

                        if (event.target.value == 'Create New Customer' && !customer) {
                            Alpine.store('openCreateCustomerModal', true);
                            return;
                        }

                        if (customer) {
                            Alpine.store('openCreateCustomerModal', false);
                            select2.append(new Option(customer.company_name, customer.id, false, false));
                            select2.val(customer.id);
                            select2.trigger('change');
                        }
                    });
                }
            }));
        });
    </script>
@endpush

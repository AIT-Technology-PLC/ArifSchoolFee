@extends('layouts.app')

@section('title', 'Create New Contact')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Contact" />
        <form
            id="formOne"
            action="{{ route('contacts.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="ContactMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('Contact.*')) }})"
            >
                <template
                    x-for="(Contact, index) in Contacts"
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
                                    <x-forms.label x-bind:for="`Contact[${index}][name]`">
                                        Name <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`Contact[${index}][name]`"
                                                x-bind:name="`Contact[${index}][name]`"
                                                x-model="Contact.name"
                                                placeholder="Contact Name"
                                            />
                                            <x-common.icon
                                                name="fas fa-address-book"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`Contact.${index}.name`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.label x-bind:for="`Contact[${index}][tin]`">
                                        TIN <sup class="has-text-danger"> </sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="number"
                                                x-bind:id="`Contact[${index}][tin]`"
                                                x-bind:name="`Contact[${index}][tin]`"
                                                x-model="Contact.tin"
                                                placeholder="Tin No"
                                            />
                                            <x-common.icon
                                                name="fas fa-hashtag"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`Contact.${index}.tin`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.label x-bind:for="`Contact[${index}][email]`">
                                        Email <sup class="has-text-danger"> </sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`Contact[${index}][email]`"
                                                x-bind:name="`Contact[${index}][email]`"
                                                x-model="Contact.email"
                                                placeholder="Email Address"
                                            />
                                            <x-common.icon
                                                name="fas fa-at"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`Contact.${index}.email`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.label x-bind:for="`Contact[${index}][phone]`">
                                        Phone <sup class="has-text-danger"> </sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`Contact[${index}][phone]`"
                                                x-bind:name="`Contact[${index}][phone]`"
                                                x-model="Contact.phone"
                                                placeholder="Phone/Telephone"
                                            />
                                            <x-common.icon
                                                name="fas fa-phone"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`Contact.${index}.phone`)"
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
            Alpine.data("ContactMasterDetailForm", ({
                Contact
            }) => ({
                Contacts: [],

                async init() {
                    if (Contact) {
                        this.Contacts = Contact;

                        return;
                    }

                    this.add();
                },

                add() {
                    this.Contacts.push({});
                },

                async remove(index) {
                    if (this.Contacts.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.Contacts.splice(index, 1));

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

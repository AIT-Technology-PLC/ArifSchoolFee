@extends('layouts.app')

@section('title', 'Create Warnings')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Warning" />
        <form
            id="formOne"
            action="{{ route('warnings.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="warningMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('warning.*')) }})"
            >
                <template
                    x-for="(warning, index) in warnings"
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
                                        <x-forms.label x-bind:for="`warning[${index}][employee_id]`">
                                            Employee Name <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                x-bind:id="`warning[${index}][employee_id]`"
                                                x-bind:name="`warning[${index}][employee_id]`"
                                                x-model="warning.employee_id"
                                            >
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->employee->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-user"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`warning.${index}.employee_id`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`warning[${index}][type]`">
                                            Type <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left ">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                x-bind:id="`warning[${index}][type]`"
                                                x-bind:name="`warning[${index}][type]`"
                                                x-model="warning.type"
                                            >
                                                <option value="Initial Warning">Initial Warning</option>
                                                <option value="Affirmation Warning">Affirmation Warning</option>
                                                <option value="Final Warning">Final Warning</option>
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-sort"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`warning.${index}.type`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`warning[${index}][issued_on]`">
                                            Issued on <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                x-bind:id="`warning[${index}][issued_on]`"
                                                x-bind:name="`warning[${index}][issued_on]`"
                                                x-model="warning.issued_on"
                                                type="datetime-local"
                                                placeholder="mm/dd/yyyy"
                                            />
                                            <x-common.icon
                                                name="fas fa-calendar-alt"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`warning.${index}.issued_on`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-12">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`warning[${index}][letter]`">
                                            Reason <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control>
                                            <x-forms.textarea
                                                rows="5"
                                                class="summernote-details"
                                                x-bind:id="`warning[${index}][letter]`"
                                                x-bind:name="`warning[${index}][letter]`"
                                                x-init="summernote(index)"
                                                x-model="warning.letter"
                                            ></x-forms.textarea>
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`warning.${index}.letter`)"
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
            Alpine.data("warningMasterDetailForm", ({
                warning
            }) => ({
                warnings: [],

                init() {
                    if (warning) {
                        this.warnings = warning;
                        return;
                    }

                    this.add();
                },
                add() {
                    this.warnings.push({});
                },
                async remove(index) {
                    if (this.warnings.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.warnings.splice(index, 1));

                    await Promise.resolve(
                        this.warnings.forEach((warning, i) => {
                            if (i >= index) {
                                $(".summernote-details").eq(i).summernote("code", warning.letter);
                            }
                        })
                    );

                    Pace.restart();
                },
                summernote(index) {
                    let object = this;

                    let summernote = $(this.$el).summernote({
                        placeholder: "Write description or other notes here",
                        tabsize: 2,
                        minHeight: 90,
                        tabDisable: true,
                        toolbar: [
                            ["font", ["bold"]],
                            ["table", ["table"]],
                            ["forecolor", ["forecolor"]],
                        ],
                        callbacks: {
                            onInit: function() {
                                $(this).summernote("code", object.warnings[index].letter);
                            },
                            onChange: function(contents, $editable) {
                                object.warnings[index].letter = contents;
                            }
                        }
                    });
                },
            }));
        });
    </script>
@endpush

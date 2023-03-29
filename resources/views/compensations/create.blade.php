@extends('layouts.app')

@section('title', 'Create Compensation')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Compensation" />
        <form
            id="formOne"
            action="{{ route('compensations.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="compensationMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('compensation.*')) }})"
            >
                <template
                    x-for="(compensation, index) in compensations"
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
                                    <x-forms.label x-bind:for="`compensation[${index}][name]`">
                                        Name <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`compensation[${index}][name]`"
                                                x-bind:name="`compensation[${index}][name]`"
                                                x-model="compensation.name"
                                                placeholder="Name"
                                            />
                                            <x-common.icon
                                                name="fa-solid fa-circle-dollar-to-slot"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.name`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`compensation[${index}][type]`">
                                            Type <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left ">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                x-bind:id="`compensation[${index}][type]`"
                                                x-bind:name="`compensation[${index}][type]`"
                                                x-model="compensation.type"
                                            >
                                                <option value="earning ">Earning</option>
                                                <option value="deduction">Deduction</option>
                                                <option value="none">None</option>
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-sort"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.type`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`compensation[${index}][depends_on]`">
                                            Depends On <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                x-bind:id="`compensation[${index}][depends_on]`"
                                                x-bind:name="`compensation[${index}][depends_on]`"
                                                x-model="compensation.depends_on"
                                            >
                                                <option
                                                    value=""
                                                    selected
                                                    hidden
                                                >
                                                    Select Compensation
                                                </option>
                                                @foreach ($compensations as $compensation)
                                                    <option value="{{ $compensation->id }}">{{ $compensation->name }}</option>
                                                @endforeach
                                                <option value="">None</option>
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-sort"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.depends_on`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.label x-bind:for="`compensation[${index}][percentage]`">
                                        Percentage <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="number"
                                                x-bind:id="`compensation[${index}][percentage]`"
                                                x-bind:name="`compensation[${index}][percentage]`"
                                                x-model="compensation.percentage"
                                                placeholder="Percentage"
                                            />
                                            <x-common.icon
                                                name="fas fa-percent"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.percentage`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.label x-bind:for="`compensation[${index}][default_value]`">
                                        Default Value <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="number"
                                                x-bind:id="`compensation[${index}][default_value]`"
                                                x-bind:name="`compensation[${index}][default_value]`"
                                                x-model="compensation.default_value"
                                                placeholder="Default Value"
                                            />
                                            <x-common.icon
                                                name="fas fa-th"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.default_value`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.label x-bind:for="`compensation[${index}][maximum_amount]`">
                                        Maximum Amount <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="number"
                                                x-bind:id="`compensation[${index}][maximum_amount]`"
                                                x-bind:name="`compensation[${index}][maximum_amount]`"
                                                x-model="compensation.maximum_amount"
                                                placeholder="Default Value"
                                            />
                                            <x-common.icon
                                                name="fas fa-th"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.maximum_amount`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`compensation[${index}][is_active]`">
                                            Active or not <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control>
                                            <label class="radio has-text-grey has-text-weight-normal">
                                                <input
                                                    x-bind:id="`compensation[${index}][is_active]`"
                                                    x-bind:name="`compensation[${index}][is_active]`"
                                                    x-model="compensation.is_active"
                                                    type="radio"
                                                    value="1"
                                                    class="mt-3"
                                                >
                                                Active
                                            </label>
                                            <label class="radio has-text-grey has-text-weight-normal mt-2">
                                                <input
                                                    type="radio"
                                                    x-bind:id="`compensation[${index}][is_active]`"
                                                    x-bind:name="`compensation[${index}][is_active]`"
                                                    x-model="compensation.is_active"
                                                    value="0"
                                                >
                                                Not Active
                                            </label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.is_active`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`compensation[${index}][has_formula]`">
                                            Formula or Amount <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control>
                                            <label class="radio has-text-grey has-text-weight-normal">
                                                <input
                                                    x-bind:id="`compensation[${index}][has_formula]`"
                                                    x-bind:name="`compensation[${index}][has_formula]`"
                                                    x-model="compensation.has_formula"
                                                    type="radio"
                                                    value="1"
                                                    class="mt-3"
                                                >
                                                Formula-based
                                            </label>
                                            <label class="radio has-text-grey has-text-weight-normal mt-2">
                                                <input
                                                    type="radio"
                                                    x-bind:id="`compensation[${index}][has_formula]`"
                                                    x-bind:name="`compensation[${index}][has_formula]`"
                                                    x-model="compensation.has_formula"
                                                    value="0"
                                                >
                                                Amount-based
                                            </label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.has_formula`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`compensation[${index}][is_taxable]`">
                                            Taxable or not <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control>
                                            <label class="radio has-text-grey has-text-weight-normal">
                                                <input
                                                    x-bind:id="`compensation[${index}][is_taxable]`"
                                                    x-bind:name="`compensation[${index}][is_taxable]`"
                                                    x-model="compensation.is_taxable"
                                                    type="radio"
                                                    value="1"
                                                    class="mt-3"
                                                >
                                                Taxable
                                            </label>
                                            <label class="radio has-text-grey has-text-weight-normal mt-2">
                                                <input
                                                    type="radio"
                                                    x-bind:id="`compensation[${index}][is_taxable]`"
                                                    x-bind:name="`compensation[${index}][is_taxable]`"
                                                    x-model="compensation.is_taxable"
                                                    value="0"
                                                >
                                                Not Taxable
                                            </label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.is_taxable`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`compensation[${index}][is_adjustable]`">
                                            Adjustable or not <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control>
                                            <label class="radio has-text-grey has-text-weight-normal">
                                                <input
                                                    x-bind:id="`compensation[${index}][is_adjustable]`"
                                                    x-bind:name="`compensation[${index}][is_adjustable]`"
                                                    x-model="compensation.is_adjustable"
                                                    type="radio"
                                                    value="1"
                                                    class="mt-3"
                                                >
                                                Adjustable
                                            </label>
                                            <label class="radio has-text-grey has-text-weight-normal mt-2">
                                                <input
                                                    type="radio"
                                                    x-bind:id="`compensation[${index}][is_adjustable]`"
                                                    x-bind:name="`compensation[${index}][is_adjustable]`"
                                                    x-model="compensation.is_adjustable"
                                                    value="0"
                                                >
                                                Not Adjustable
                                            </label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.is_adjustable`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`compensation[${index}][can_be_inputted_manually]`">
                                            Can be inputted manually or not <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control>
                                            <label class="radio has-text-grey has-text-weight-normal">
                                                <input
                                                    x-bind:id="`compensation[${index}][can_be_inputted_manually]`"
                                                    x-bind:name="`compensation[${index}][can_be_inputted_manually]`"
                                                    x-model="compensation.can_be_inputted_manually"
                                                    type="radio"
                                                    value="1"
                                                    class="mt-3"
                                                >
                                                Inputted Manually
                                            </label>
                                            <label class="radio has-text-grey has-text-weight-normal mt-2">
                                                <input
                                                    type="radio"
                                                    x-bind:id="`compensation[${index}][can_be_inputted_manually]`"
                                                    x-bind:name="`compensation[${index}][can_be_inputted_manually]`"
                                                    x-model="compensation.can_be_inputted_manually"
                                                    value="0"
                                                >
                                                Not Inputted Manually
                                            </label>
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`compensation.${index}.can_be_inputted_manually`)"
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
            Alpine.data("compensationMasterDetailForm", ({
                compensation
            }) => ({
                compensations: [],

                init() {
                    if (compensation) {
                        this.compensations = compensation;

                        return;
                    }

                    this.add();
                },
                add() {
                    this.compensations.push({});
                },
                remove(index) {
                    if (this.compensations.length <= 0) {
                        return;
                    }

                    this.compensations.splice(index, 1);

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

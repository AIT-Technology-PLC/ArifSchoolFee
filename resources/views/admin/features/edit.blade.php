<div
    x-data="toggler"
    @open-company-features-modal.window="toggle"
    class="modal is-active"
    x-cloak
    x-show="!isHidden"
    x-transition
>
    <div
        class="modal-background"
        @click="toggle"
    ></div>
    <div class="modal-content p-lr-20">
        <x-common.content-wrapper>
            <x-content.header title="Manage Features" />
            <form
                id="manage-features"
                action="{{ route('admin.companies.features.update', $company->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-12">
                            <x-forms.field>
                                <x-forms.label for="enable[]">
                                    Enable Features <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        x-init="initializeSelect2($el, '')"
                                        class="is-fullwidth is-multiple"
                                        id="enable[]"
                                        name="enable[]"
                                        multiple
                                        style="width: 100% !important"
                                    >
                                        @foreach ($features as $feature)
                                            <option
                                                value="{{ $feature->id }}"
                                                @selected(in_array($feature->id, old('enable', [])))
                                            >
                                                {{ $feature->name }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.validation-error property="enable.*" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-12">
                            <x-forms.field>
                                <x-forms.label for="disable[]">
                                    Disable Features <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        x-init="initializeSelect2($el, '')"
                                        class="is-fullwidth is-multiple"
                                        id="disable[]"
                                        name="disable[]"
                                        multiple
                                        style="width: 100% !important"
                                    >
                                        @foreach ($features as $feature)
                                            <option
                                                value="{{ $feature->id }}"
                                                @selected(in_array($feature->id, old('disable', [])))
                                            >
                                                {{ $feature->name }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.validation-error property="disable.*" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    </div>
                </x-content.main>
                <x-content.footer>
                    <x-common.save-button />
                </x-content.footer>
            </form>
        </x-common.content-wrapper>
    </div>
    <x-common.button
        tag="button"
        class="modal-close is-large"
        @click="toggle"
    />
</div>

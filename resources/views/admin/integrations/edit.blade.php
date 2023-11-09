<div
    x-data="toggler"
    @open-company-integrations-modal.window="toggle"
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
            <x-content.header title="Manage Integrations" />
            <form
                id="manage-integrations"
                action="{{ route('admin.companies.integrations.update', $company->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-12">
                            <x-forms.field>
                                <x-forms.label for="integrations[]">
                                    Integrations <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        x-init="initializeSelect2($el, '')"
                                        class="is-fullwidth is-multiple"
                                        id="integrations[]"
                                        name="integrations[]"
                                        multiple
                                        style="width: 100% !important"
                                    >
                                        @foreach ($integrations as $integration)
                                            <option
                                                value="{{ $integration->id }}"
                                                @selected(in_array($integration->id, old('integrations', $company->integrations->pluck('id')->toArray())))
                                            >
                                                {{ $integration->name }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.validation-error property="integrations.*" />
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

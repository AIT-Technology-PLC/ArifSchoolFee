<div
    x-data="toggler"
    @open-company-reset-modal.window="toggle"
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
            <x-content.header title="Reset Account" />
            <form
                id="manage-reset"
                action="{{ route('admin.companies.reset', $company->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-4">
                            <x-forms.field>
                                <x-forms.label for="reset_master">
                                    Reset Master Data <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="reset_master"
                                        name="reset_master"
                                    >
                                        <option
                                            value="1"
                                            @selected(old('reset_master'))
                                        > Reset </option>
                                        <option
                                            value="0"
                                            @selected(!old('reset_master'))
                                        > No </option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-chart-bar"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="reset_master" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-4">
                            <x-forms.field>
                                <x-forms.label for="reset_transaction">
                                    Reset Transaction <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="reset_transaction"
                                        name="reset_transaction"
                                    >
                                        <option
                                            value="1"
                                            @selected(old('reset_transaction'))
                                        > Reset </option>
                                        <option
                                            value="0"
                                            @selected(!old('reset_transaction'))
                                        > No </option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-dollar-sign"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="reset_transaction" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-12">
                            <x-forms.field>
                                <x-forms.label for="tables[]">
                                    Reset By Feature <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        x-init="initializeSelect2($el, '')"
                                        class="is-fullwidth is-multiple"
                                        id="tables[]"
                                        name="tables[]"
                                        multiple
                                        style="width: 100% !important"
                                    >
                                        @foreach ($tables as $table)
                                            <option
                                                value="{{ $table }}"
                                                @selected(in_array($table, old('tables', [])))
                                            >
                                                {{ str($table)->headline() }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.validation-error property="tables.*" />
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

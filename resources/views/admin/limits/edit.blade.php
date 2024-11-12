<div
    x-data="toggler"
    @open-company-limits-modal.window="toggle"
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
            <x-content.header title="Manage Resources" />
            <form
                id="manage-resources"
                action="{{ route('admin.schools.limits.update', $school->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    <div class="columns is-marginless is-multiline">
                        @foreach ($limits as $limit)
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="limit[{{ $limit->id }}][amount]">
                                        Number of {{ str($limit->name)->title()->plural() }} <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="limit[{{ $limit->id }}][amount]"
                                            name="limit[{{ $limit->id }}][amount]"
                                            type="number"
                                            placeholder="Number of {{ str($limit->name)->title()->plural() }}"
                                            value="{{ old('limit')[$limit->id]['amount'] ?? ($limit->getLimitAmountOfCompany($school) ?? null) }}"
                                        />
                                        <x-common.icon
                                            name="fas fa-cubes"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="limit.{{ $limit->id }}.amount" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endforeach
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

<div
    x-data="toggler"
    @open-company-subscriptions-modal.window="toggle"
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
            <x-content.header title="Create Subscription" />
            <form
                id="manage-subscriptions"
                action="{{ route('admin.companies.subscriptions.store', $company->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="plan_id">
                                    Plan <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="plan_id"
                                        name="plan_id"
                                    >
                                        <option
                                            selected
                                            disabled
                                        >Select Plan</option>
                                        @foreach ($plans as $plan)
                                            <option
                                                value="{{ $plan->id }}"
                                                @selected($plan->id == old('plan_id', $company->plan_id))
                                            >
                                                {{ $plan->name }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-tag"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="plan_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.label for="starts_on">
                                Subscription Months <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="months"
                                        name="months"
                                        type="number"
                                        placeholder="Months (e.g. 12)"
                                        value="{{ old('months', 12) }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-calendar"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="months" />
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

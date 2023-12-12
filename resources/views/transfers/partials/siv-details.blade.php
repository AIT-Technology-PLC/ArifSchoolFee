<div
    x-data="toggler"
    @open-siv-details-modal.window="toggle"
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
            <x-content.header title="Issue SIV" />
            <form
                id="convert_to_siv"
                action="{{ route('transfers.convert_to_siv', $transfer->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-6">
                            <x-forms.label for="master[received_by]">
                                Received By <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="text"
                                        name="master[received_by]"
                                        id="master[received_by]"
                                        placeholder="Reciever Name"
                                        value="{{ old('master')['received_by'] ?? '' }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-user"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="master.received_by" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.label for="master[delivered_by]">
                                Delivered By <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        type="text"
                                        name="master[delivered_by]"
                                        id="master[delivered_by]"
                                        placeholder="Delivered By"
                                        value="{{ old('master')['delivered_by'] ?? '' }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-user"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="master.delivered_by" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <x-common.custom-field-form
                            model-type="siv"
                            :input="$transfer->convertedCustomFields('siv')"
                        />
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

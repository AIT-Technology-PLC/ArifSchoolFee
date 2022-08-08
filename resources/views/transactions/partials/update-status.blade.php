<div
    x-data="toggler"
    @open-update-status-modal.window="toggle"
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
            <x-content.header title="Update Status" />
            <form
                id="formOne"
                action="{{ route('transactions.update_status', $transaction->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    <x-forms.field>
                        <x-forms.label for="status">
                            Status <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.control class="has-icons-left ">
                            <x-forms.select
                                class="is-fullwidth"
                                id="status"
                                name="status"
                            >
                                <option
                                    disabled
                                    selected
                                >
                                    Update Status
                                </option>
                                @foreach ($transaction->pad->padStatuses as $padStatus)
                                    <option
                                        value="{{ $padStatus->name }}"
                                        @selected($padStatus->name == $transaction->status)
                                    >
                                        {{ $padStatus->name }}
                                    </option>
                                @endforeach
                            </x-forms.select>
                            <x-common.icon
                                name="fas fa-info"
                                class="is-small is-left"
                            />
                            <x-common.validation-error property="status" />
                        </x-forms.control>
                    </x-forms.field>
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

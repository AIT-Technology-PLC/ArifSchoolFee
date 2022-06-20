@props(['title', 'action', 'jobDetails'])

<div
    x-data="toggler"
    @open-update-wip-modal.window="toggle"
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
            <x-content.header title="{{ $title }}" />
            <form
                id="job-to-available"
                action="{{ $action }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    @foreach ($jobDetails as $jobDetail)
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-3">
                                <x-forms.field>
                                    <x-forms.label for="product_id">
                                        Product <sup class="has-text-danger"> </sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="product_id"
                                            name="job[{{ $loop->index }}][product_id]"
                                            readonly
                                        >
                                            <option value="{{ $jobDetail->product_id }}">{{ $jobDetail->product->name }}</option>
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-th"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="product_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <x-forms.field>
                                    <x-forms.label for="quantity">
                                        Quantity <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            type="input"
                                            name="job[{{ $loop->index }}][quantity]"
                                            id="quantity"
                                            readonly
                                            value="{{ $jobDetail->quantity }}"
                                        />
                                        <x-common.icon
                                            name="fas fa-balance-scale"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="quantity" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <x-forms.field>
                                    <x-forms.label for="wip">
                                        Wip <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            type="input"
                                            name="job[{{ $loop->index }}][wip]"
                                            id="wip"
                                            readonly
                                            value="{{ $jobDetail->wip }}"
                                        />
                                        <x-common.icon
                                            name="fas fa-balance-scale"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="wip" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <x-forms.field>
                                    <x-forms.label for="available">
                                        Available <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            type="input"
                                            name="job[{{ $loop->index }}][available]"
                                            id="available"
                                            value="{{ old('available') }}"
                                        />
                                        <x-common.icon
                                            name="fas fa-balance-scale"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="available" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        </div>
                    @endforeach
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

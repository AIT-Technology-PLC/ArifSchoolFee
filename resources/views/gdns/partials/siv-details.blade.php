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
            <x-content.header title="Siv Details" />
            <form
                id="convert_to_siv"
                action="{{ route('gdns.convert_to_siv', $gdn->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    @foreach ($gdnDetails as $gdnDetail)
                        @if (!$gdnDetail->isFullyDelivered())
                            <div class="columns is-marginless is-multiline">
                                <div class="column {{ $gdnDetail->product->isBatchable() }}? is-4:is-6">
                                    <x-forms.field>
                                        <x-forms.label class="is-size-7">
                                            Product <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                name="gdn[{{ $loop->index }}][product_id]"
                                                readonly
                                            >
                                                <option value="{{ $gdnDetail->product_id }}">{{ $gdnDetail->product->name }}</option>
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-th"
                                                class="is-large is-left"
                                            />
                                            <x-common.validation-error property="gdn.{{ $loop->index }}.product_id" />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column {{ $gdnDetail->product->isBatchable() }}? is-4:is-6">
                                    <x-forms.field>
                                        <x-forms.label class="is-size-7">
                                            Warehouse <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                name="gdn[{{ $loop->index }}][warehouse_id]"
                                                readonly
                                            >
                                                <option value="{{ $gdnDetail->warehouse_id }}">{{ $gdnDetail->Warehouse->name }}</option>
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-th"
                                                class="is-large is-left"
                                            />
                                            <x-common.validation-error property="gdn.{{ $loop->index }}.warehouse_id" />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                @if ($gdnDetail->product->isBatchable())
                                    <div class="column is-4">
                                        <x-forms.field>
                                            <x-forms.label class="is-size-7">
                                                Batch <sup class="has-text-danger"></sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.select
                                                    class="is-fullwidth"
                                                    name="gdn[{{ $loop->index }}][merchandise_batch_id]"
                                                    readonly
                                                >
                                                    <option value="{{ $gdnDetail->merchandise_batch_id }}">{{ $gdnDetail->merchandiseBatch?->batch_no }}</option>
                                                </x-forms.select>
                                                <x-common.icon
                                                    name="fas fa-th"
                                                    class="is-large is-left"
                                                />
                                                <x-common.validation-error property="gdn.{{ $loop->index }}.product_id" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                @endif
                                <div class="column {{ $gdnDetail->product->isBatchable() }}? is-4:is-6"">
                                    <x-forms.field>
                                        <x-forms.label class="is-size-7">
                                            Quantity <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                type="number"
                                                name="gdn[{{ $loop->index }}][quantity]"
                                                value="{{ $gdnDetail->quantity - $gdnDetail->delivered_quantity }}"
                                            />
                                            <x-common.icon
                                                name="fas fa-balance-scale"
                                                class="is-large is-left"
                                            />
                                            <x-common.validation-error property="gdn.{{ $loop->index }}.quantity" />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                            </div>
                        @endif
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

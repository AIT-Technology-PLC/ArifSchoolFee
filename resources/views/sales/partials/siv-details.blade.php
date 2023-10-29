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
                action="{{ route('sales.convert_to_siv', $sale->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    @foreach ($saleDetails as $saleDetail)
                        @continue($saleDetail->isFullyDelivered())

                        <div class="columns is-marginless is-multiline">
                            <div class="column">
                                <x-forms.field>
                                    <x-forms.label class="is-size-7">
                                        Product <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth is-small"
                                            name="sale[{{ $loop->index }}][product_id]"
                                            readonly
                                        >
                                            <option value="{{ $saleDetail->product_id }}">{{ $saleDetail->product->name }}</option>
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-th"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="sale.{{ $loop->index }}.product_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column">
                                <x-forms.field>
                                    <x-forms.label class="is-size-7">
                                        Warehouse <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth is-small"
                                            name="sale[{{ $loop->index }}][warehouse_id]"
                                            readonly
                                        >
                                            <option value="{{ $saleDetail->warehouse_id }}">{{ $saleDetail->warehouse->name }}</option>
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-th"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="sale.{{ $loop->index }}.warehouse_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            @if ($saleDetail->product->isBatchable())
                                <div class="column">
                                    <x-forms.field>
                                        <x-forms.label class="is-size-7">
                                            Batch <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.select
                                                class="is-fullwidth is-small"
                                                name="sale[{{ $loop->index }}][merchandise_batch_id]"
                                                readonly
                                            >
                                                <option value="{{ $saleDetail->merchandise_batch_id }}">{{ $saleDetail->merchandiseBatch?->batch_no }}</option>
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-th"
                                                class="is-large is-left"
                                            />
                                            <x-common.validation-error property="sale.{{ $loop->index }}.merchandise_batch_id" />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                            @endif
                            <div class="column">
                                <x-forms.field>
                                    <x-forms.label class="is-size-7">
                                        Quantity <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            class="is-small"
                                            type="number"
                                            name="sale[{{ $loop->index }}][quantity]"
                                            value="{{ $saleDetail->quantity - $saleDetail->delivered_quantity }}"
                                        />
                                        <x-common.icon
                                            name="fas fa-balance-scale"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="sale.{{ $loop->index }}.quantity" />
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

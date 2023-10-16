<div>
    <x-common.content-wrapper>
        <x-content.header title="{{ isset($transaction) ? 'Edit' : 'New' }} {{ $pad->name }}" />
        <form
            id="formOne"
            method="POST"
            enctype="multipart/form-data"
            novalidate
            wire:submit="{{ isset($transaction) ? 'update' : 'store' }}"
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                {{ $pad->abbreviation }} Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    id="code"
                                    wire:model.blur="code"
                                    wire:key="code"
                                    :readonly="!userCompany()->isEditingReferenceNumberEnabled()"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if ($padStatuses->isNotEmpty())
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="status">
                                    Status <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="status"
                                        wire:model.blur="status"
                                        wire:key="status"
                                    >
                                        <option
                                            selected
                                            hidden
                                            value=""
                                        >
                                            Select Status
                                        </option>
                                        @foreach ($padStatuses as $padStatus)
                                            <option value="{{ $padStatus->name }}">
                                                {{ $padStatus->name }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-info"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="status" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="issued_on">
                                Issued On <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="datetime-local"
                                    id="issued_on"
                                    placeholder="mm/dd/yyyy"
                                    wire:model.blur="issued_on"
                                    wire:key="issued_on"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="issued_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @foreach ($masterPadFields as $masterPadField)
                        @if ($masterPadField->hasRelation())
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label
                                        for="{{ $masterPadField->id }}"
                                        class="label text-green has-text-weight-normal"
                                    >
                                        {{ $masterPadField->label }} <sup class="has-text-danger">{{ $masterPadField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="control has-icons-left">
                                        <div
                                            class="select is-fullwidth"
                                            wire:ignore
                                        >
                                            <x-dynamic-component
                                                :component="$masterPadField->padRelation->component_name"
                                                :selected-id="$master[$masterPadField->id] ?? ''"
                                                type="{{ $masterPadField->padRelation->model_name == 'Warehouse' ? $pad->inventory_operation_type : '' }}"
                                                id="{{ $masterPadField->id }}"
                                                x-init="initSelect2($el, '{{ $masterPadField->label }}');
                                                bindData($el, 'master.{{ $masterPadField->id }}')"
                                            />
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="{{ $masterPadField->icon }}"></i>
                                        </div>
                                        <x-common.validation-error property="master.{{ $masterPadField->id }}" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @elseif ($masterPadField->isTagInput() && !$masterPadField->isInputTypeCheckbox() && !$masterPadField->isInputTypeRadio() && !$masterPadField->isInputTypeFile())
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="{{ $masterPadField->id }}">
                                        {{ $masterPadField->label }} <sup class="has-text-danger">{{ $masterPadField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            type="{{ $masterPadField->tag_type }}"
                                            id="{{ $masterPadField->id }}"
                                            wire:model.blur="master.{{ $masterPadField->id }}"
                                            wire:key="master.{{ $masterPadField->id }}"
                                            :readonly="$masterPadField->isReadonly()"
                                        />
                                        <x-common.icon
                                            name="{{ $masterPadField->icon }}"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="master.{{ $masterPadField->id }}" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @elseif ($masterPadField->isInputTypeFile())
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="{{ $masterPadField->id }}">
                                        {{ $masterPadField->label }}
                                        <sup class="has-text-danger">
                                            {{ $masterPadField->isRequired() ? '*' : '' }}
                                        </sup>
                                    </x-forms.label>
                                    <div class="file has-name">
                                        <label class="file-label">
                                            <x-forms.input
                                                class="file-input"
                                                type="file"
                                                wire:model.blur="master.{{ $masterPadField->id }}"
                                                wire:key="master.{{ $masterPadField->id }}"
                                            />
                                            <span class="file-cta bg-green has-text-white">
                                                <x-common.icon
                                                    name="fas fa-upload"
                                                    class="file-icon"
                                                />
                                                <span class="file-label">
                                                    Upload {{ $masterPadField->label }}
                                                </span>
                                            </span>
                                            <span class="file-name">
                                                {{ $master[$masterPadField->id] ?? '' }}
                                            </span>
                                        </label>
                                    </div>
                                    <div
                                        class="mt-3"
                                        wire:loading
                                        wire:target="master.{{ $masterPadField->id }}"
                                    >
                                        <span class="icon text-gold">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </span>
                                        <span class="text-gold is-uppercase">
                                            Uploading...
                                        </span>
                                    </div>
                                    <x-common.validation-error property="master.{{ $masterPadField->id }}" />
                                </x-forms.field>
                            </div>
                        @elseif ($masterPadField->isInputTypeRadio())
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="{{ $masterPadField->id }}">
                                        {{ $masterPadField->label }} <sup class="has-text-danger">{{ $masterPadField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control>
                                        <label class="radio has-text-grey">
                                            <input
                                                type="radio"
                                                id="{{ $masterPadField->id }}"
                                                name="{{ $masterPadField->id }}"
                                                wire:model.blur="master.{{ $masterPadField->id }}"
                                                wire:key="master.{{ $masterPadField->id }}"
                                                value="Yes"
                                            >
                                            Yes
                                        </label>
                                        <label class="radio has-text-grey mt-2">
                                            <input
                                                type="radio"
                                                id="{{ $masterPadField->id }}"
                                                name="{{ $masterPadField->id }}"
                                                wire:model.blur="master.{{ $masterPadField->id }}"
                                                wire:key="master.{{ $masterPadField->id }}"
                                                value="No"
                                            >
                                            No
                                        </label>
                                        <x-common.validation-error property="master.{{ $masterPadField->id }}" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @elseif($masterPadField->isTagTextarea())
                            <div class="column is-12">
                                <x-forms.field>
                                    <x-forms.label for="{{ $masterPadField->id }}">
                                        {{ $masterPadField->label }} <sup class="has-text-danger">{{ $masterPadField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control
                                        class="has-icons-left"
                                        wire:ignore
                                    >
                                        <x-forms.textarea
                                            id="{{ $masterPadField->id }}"
                                            x-init="summerNote($el, 'master.{{ $masterPadField->id }}')"
                                        >
                                        </x-forms.textarea>
                                        <x-common.validation-error property="master.{{ $masterPadField->id }}" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @elseif(!$masterPadField->hasRelation() && $masterPadField->isTagSelect())
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="{{ $masterPadField->id }}">
                                        {{ $masterPadField->label }} <sup class="has-text-danger">{{ $masterPadField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="{{ $masterPadField->id }}"
                                            wire:model.blur="master.{{ $masterPadField->id }}"
                                            wire:key="master.{{ $masterPadField->id }}"
                                        >
                                            <option
                                                selected
                                                hidden
                                            >
                                                Select {{ $masterPadField->label }}
                                            </option>
                                            @foreach (explode(',', $masterPadField->tag_type) as $option)
                                                <option value="{{ $option }}">
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="{{ $masterPadField->icon }}"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="master.{{ $masterPadField->id }}" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endif
                    @endforeach
                </div>
                @if ($pad->hasDetailPadFields())
                    @foreach ($details as $detail)
                        <div class="mx-3">
                            <div class="field has-addons mb-0 mt-5">
                                <div class="control">
                                    <span class="tag bg-green has-text-white is-medium is-radiusless">
                                        Item {{ $loop->iteration }}
                                    </span>
                                </div>
                                <div class="control">
                                    <button
                                        type="button"
                                        class="tag bg-lightgreen has-text-white is-medium is-radiusless is-pointer"
                                        wire:click="removeDetail({{ $loop->index }})"
                                    >
                                        <span class="icon text-green">
                                            <i class="fas fa-times-circle"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="box has-background-white-bis radius-top-0">
                                <div class="columns is-marginless is-multiline">
                                    @foreach ($detailPadFields as $detailPadField)
                                        @if ($detailPadField->hasRelation() && $detailPadField->padRelation->model_name == 'Product')
                                            <div class="column is-6">
                                                <x-forms.label for="{{ $loop->parent->index }}{{ $detailPadField->id }}">
                                                    {{ $detailPadField->label }} <sup class="has-text-danger">{{ $detailPadField->isRequired() ? '*' : '' }}</sup>
                                                </x-forms.label>
                                                <x-forms.field
                                                    class="has-addons"
                                                    x-data="productDataProvider({{ $detail[$detailPadField->id] ?? '' }})"
                                                >
                                                    <x-forms.control
                                                        class="has-icons-left"
                                                        style="width: 30%"
                                                    >
                                                        <x-common.category-list
                                                            x-model="selectedCategory"
                                                            x-on:change="getProductsByCategory"
                                                        />
                                                    </x-forms.control>
                                                    <x-forms.control
                                                        class="has-icons-left is-expanded"
                                                        data-selected-value="{{ $detail[$detailPadField->id] ?? '' }}"
                                                    >
                                                        <x-common.product-list
                                                            class="select2-picker"
                                                            tags="false"
                                                            key=""
                                                            x-init="select2;
                                                            bindData($el, 'details.{{ $loop->parent->index }}.{{ $detailPadField->id }}')"
                                                            wire:ignore
                                                            :only-single-products="$pad->isInventoryOperationAdd()"
                                                        />
                                                        <x-common.icon
                                                            name="fas fa-th"
                                                            class="is-large is-left"
                                                        />
                                                        <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                        @elseif ($detailPadField->hasRelation() && $detailPadField->padRelation->model_name == 'Merchandise Batch' && userCompany()->canSelectBatchNumberOnForms() && !$pad->isInventoryOperationAdd() && $products->find($details[$loop->parent->index][$productPadField?->id] ?? null)?->isBatchable())
                                            @php
                                                $merchandiseBatches = \App\Models\MerchandiseBatch::available()
                                                    ->when(!is_null($warehousePadField), fn($q) => $q->whereRelation('merchandise', 'warehouse_id', $details[$loop->parent->index][$warehousePadField->id] ?? null))
                                                    ->when(!is_null($productPadField), fn($q) => $q->whereRelation('merchandise', 'product_id', $details[$loop->parent->index][$productPadField->id] ?? null))
                                                    ->orderBy('expires_on')
                                                    ->get();
                                            @endphp
                                            <div class="column is-6">
                                                <x-forms.field>
                                                    <x-forms.label
                                                        for="{{ $loop->parent->index }}{{ $detailPadField->id }}"
                                                        class="label text-green has-text-weight-normal"
                                                    >
                                                        {{ $detailPadField->label }} <sup class="has-text-danger">*</sup>
                                                    </x-forms.label>
                                                    <x-forms.control
                                                        class="control has-icons-left"
                                                        data-selected-value="{{ $detail[$detailPadField->id] ?? '' }}"
                                                    >
                                                        <x-forms.select
                                                            class="is-fullwidth"
                                                            id="{{ $loop->parent->index }}{{ $detailPadField->id }}"
                                                            wire:model.blur="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                            wire:key="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                        >
                                                            <option hidden>Select Batch</option>
                                                            @foreach ($merchandiseBatches as $merchandiseBatch)
                                                                <option value="{{ $merchandiseBatch->id }}">{{ $merchandiseBatch->batch_no }}</option>
                                                            @endforeach
                                                            <option value="">None</option>
                                                        </x-forms.select>
                                                        <div class="icon is-small is-left">
                                                            <i class="{{ $detailPadField->icon }}"></i>
                                                        </div>
                                                        <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                        @elseif ($detailPadField->hasRelation() && $detailPadField->padRelation->model_name != 'Product' && $detailPadField->padRelation->model_name != 'Merchandise Batch')
                                            <div class="column is-6">
                                                <x-forms.field>
                                                    <x-forms.label
                                                        for="{{ $loop->parent->index }}{{ $detailPadField->id }}"
                                                        class="label text-green has-text-weight-normal"
                                                    >
                                                        {{ $detailPadField->label }} <sup class="has-text-danger">{{ $detailPadField->isRequired() ? '*' : '' }}</sup>
                                                    </x-forms.label>
                                                    <x-forms.control
                                                        class="control has-icons-left"
                                                        data-selected-value="{{ $detail[$detailPadField->id] ?? '' }}"
                                                    >
                                                        <div
                                                            class="select is-fullwidth"
                                                            wire:ignore
                                                        >
                                                            <x-dynamic-component
                                                                class="select2-picker"
                                                                :component="$detailPadField->padRelation->component_name"
                                                                selected-id=""
                                                                id="{{ $loop->parent->index }}{{ $detailPadField->id }}"
                                                                x-init="initSelect2($el, '{{ $detailPadField->padRelation->model_name }}');
                                                                bindData($el, 'details.{{ $loop->parent->index }}.{{ $detailPadField->id }}')"
                                                            />
                                                        </div>
                                                        <div class="icon is-small is-left">
                                                            <i class="{{ $detailPadField->icon }}"></i>
                                                        </div>
                                                        <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                        @elseif ($detailPadField->isTagInput() && !$detailPadField->isInputTypeFile() && !$detailPadField->isInputTypeCheckbox() && !$detailPadField->isInputTypeRadio() && $detailPadField->isUnitPrice())
                                            <div class="column is-6">
                                                @if ($prices->where('product_id', $details[$loop->parent->index][$productPadField?->id] ?? null)->when(!empty($details[$loop->parent->index][$detailPadField->id]), fn($q) => $q->whereIn('fixed_price', $details[$loop->parent->index][$detailPadField->id]))->isNotEmpty())
                                                    <x-forms.field>
                                                        <x-forms.label for="{{ $loop->parent->index }}{{ $detailPadField->id }}">
                                                            {{ $detailPadField->label }}
                                                            <sup class="has-text-danger">
                                                                {{ $detailPadField->isRequired() ? '*' : '' }}
                                                            </sup>
                                                            <sup class="has-text-weight-light">
                                                                ({{ userCompany()->getPriceMethod() }})
                                                            </sup>
                                                        </x-forms.label>
                                                        <x-forms.control class="has-icons-left">
                                                            <x-forms.select
                                                                class="is-fullwidth"
                                                                id="{{ $loop->parent->index }}{{ $detailPadField->id }}"
                                                                wire:model.blur="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                                wire:key="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                            >
                                                                <option
                                                                    selected
                                                                    hidden
                                                                >
                                                                    Select Price
                                                                </option>
                                                                @foreach ($prices->where('product_id', $details[$loop->parent->index][$productPadField?->id]) as $price)
                                                                    <option value="{{ $price->fixed_price }}">
                                                                        {{ $price->fixed_price }}
                                                                    </option>
                                                                @endforeach
                                                            </x-forms.select>
                                                            <x-common.icon
                                                                name="{{ $detailPadField->icon }}"
                                                                class="is-large is-left"
                                                            />
                                                            <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                        </x-forms.control>
                                                    </x-forms.field>
                                                @else
                                                    <x-forms.field>
                                                        <x-forms.label for="{{ $loop->parent->index }}{{ $detailPadField->id }}">
                                                            {{ $detailPadField->label }}
                                                            <sup class="has-text-danger">
                                                                {{ $detailPadField->isRequired() ? '*' : '' }}
                                                            </sup>
                                                            <sup class="has-text-weight-light">
                                                                ({{ userCompany()->getPriceMethod() }})
                                                            </sup>
                                                        </x-forms.label>
                                                        <x-forms.control class="has-icons-left">
                                                            <x-forms.input
                                                                type="{{ $detailPadField->tag_type }}"
                                                                id="{{ $loop->parent->index }}{{ $detailPadField->id }}"
                                                                wire:model.blur="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                                wire:key="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                            />
                                                            <x-common.icon
                                                                name="{{ $detailPadField->icon }}"
                                                                class="is-large is-left"
                                                            />
                                                            <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                        </x-forms.control>
                                                    </x-forms.field>
                                                @endif
                                            </div>
                                        @elseif ($detailPadField->isTagInput() && !$detailPadField->isInputTypeFile() && !$detailPadField->isInputTypeCheckbox() && !$detailPadField->isInputTypeRadio() && !$detailPadField->isUnitPrice() && !$detailPadField->areBatchingFields())
                                            <div class="column is-6">
                                                <x-forms.field>
                                                    <x-forms.label for="{{ $loop->parent->index }}{{ $detailPadField->id }}">
                                                        {{ $detailPadField->label }}
                                                        <sup class="has-text-danger">
                                                            {{ $detailPadField->isRequired() ? '*' : '' }}
                                                        </sup>
                                                        @if ($pad->isInventoryOperationSubtract() && userCompany()->isInventoryCheckerEnabled() && $detailPadField->isQuantity() && !empty($details[$loop->parent->index][$productPadField?->id]) && !empty($details[$loop->parent->index][$warehousePadField?->id]))
                                                            @php
                                                                $availableQuantity =
                                                                    $merchandises
                                                                        ->where('product_id', $details[$loop->parent->index][$productPadField?->id])
                                                                        ->where('warehouse_id', $details[$loop->parent->index][$warehousePadField?->id])
                                                                        ->first()->available ?? 0;
                                                            @endphp
                                                            <sup class="tag {{ $availableQuantity <= 0 ? 'bg-lightpurple text-purple' : 'bg-lightgreen text-green' }}">
                                                                {{ number_format($availableQuantity, 2) }} {{ $products->find($details[$loop->parent->index][$productPadField?->id])?->unit_of_measurement }}
                                                            </sup>
                                                        @endif
                                                    </x-forms.label>
                                                    <x-forms.control class="has-icons-left">
                                                        <x-forms.input
                                                            type="{{ $detailPadField->tag_type }}"
                                                            id="{{ $loop->parent->index }}{{ $detailPadField->id }}"
                                                            wire:model.blur="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                            wire:key="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                            placeholder="{{ $detailPadField->isQuantity() ? $products->find($details[$loop->parent->index][$productPadField?->id] ?? null)?->unit_of_measurement : '' }}"
                                                            :readonly="$detailPadField->isReadonly()"
                                                        />
                                                        <x-common.icon
                                                            name="{{ $detailPadField->icon }}"
                                                            class="is-large is-left"
                                                        />
                                                        <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                        @elseif($pad->isInventoryOperationAdd() && $detailPadField->isBatchNoField() && $products->find($details[$loop->parent->index][$productPadField?->id] ?? null)?->isBatchable())
                                            @php
                                                $batchNoPadField = $detailPadFields->where('label', 'Batch No')->first();
                                                $batchExpiryPadField = $detailPadFields->where('label', 'Expires On')->first();
                                            @endphp
                                            <div class="column is-6">
                                                <x-forms.label for="{{ $loop->parent->index }}{{ $batchNoPadField->id }}">
                                                    Batch No <sup class="has-text-danger">*</sup>
                                                </x-forms.label>
                                                <x-forms.field class="has-addons">
                                                    <x-forms.control class="has-icons-left is-expanded">
                                                        <x-forms.input
                                                            id="{{ $loop->parent->index }}{{ $batchNoPadField->id }}"
                                                            wire:model.blur="details.{{ $loop->parent->index }}.{{ $batchNoPadField->id }}"
                                                            wire:key="details.{{ $loop->parent->index }}.{{ $batchNoPadField->id }}"
                                                            type="text"
                                                            placeholder="Batch No"
                                                        />
                                                        <x-common.icon
                                                            name="fas fa-th"
                                                            class="is-small is-left"
                                                        />
                                                        <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $batchNoPadField->id }}" />
                                                    </x-forms.control>
                                                    <x-forms.control class="has-icons-left">
                                                        <x-forms.input
                                                            id="{{ $loop->parent->index }}{{ $batchExpiryPadField->id }}"
                                                            wire:model.blur="details.{{ $loop->parent->index }}.{{ $batchExpiryPadField->id }}"
                                                            wire:key="details.{{ $loop->parent->index }}.{{ $batchExpiryPadField->id }}"
                                                            type="date"
                                                            placeholder="Expiry Date"
                                                        />
                                                        <x-common.icon
                                                            name="fas fa-calendar-alt"
                                                            class="is-small is-left"
                                                        />
                                                        <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $batchExpiryPadField->id }}" />
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                        @elseif ($detailPadField->isInputTypeRadio())
                                            <div class="column is-6">
                                                <x-forms.field>
                                                    <x-forms.label for="{{ $loop->parent->index }}{{ $detailPadField->id }}">
                                                        {{ $detailPadField->label }}
                                                        <sup class="has-text-danger">{{ $detailPadField->isRequired() ? '*' : '' }}</sup>
                                                    </x-forms.label>
                                                    <x-forms.control>
                                                        <label class="radio has-text-grey">
                                                            <input
                                                                type="radio"
                                                                id="{{ $detailPadField->id }}"
                                                                name="{{ $detailPadField->id }}"
                                                                wire:model.blur="master.{{ $detailPadField->id }}"
                                                                wire:key="master.{{ $detailPadField->id }}"
                                                                value="Yes"
                                                            >
                                                            Yes
                                                        </label>
                                                        <label class="radio has-text-grey mt-2">
                                                            <input
                                                                type="radio"
                                                                id="{{ $detailPadField->id }}"
                                                                name="{{ $detailPadField->id }}"
                                                                wire:model.blur="master.{{ $detailPadField->id }}"
                                                                wire:key="master.{{ $detailPadField->id }}"
                                                                value="No"
                                                            >
                                                            No
                                                        </label>
                                                        <x-common.validation-error property="master.{{ $detailPadField->id }}" />
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                        @elseif ($detailPadField->isTagInput() && $detailPadField->isInputTypeFile())
                                            <div class="column is-6">
                                                <x-forms.field>
                                                    <x-forms.label for="{{ $loop->parent->index }}{{ $detailPadField->id }}">
                                                        {{ $detailPadField->label }}
                                                        <sup class="has-text-danger">
                                                            {{ $detailPadField->isRequired() ? '*' : '' }}
                                                        </sup>
                                                    </x-forms.label>
                                                    <div class="file has-name">
                                                        <label class="file-label">
                                                            <x-forms.input
                                                                class="file-input"
                                                                type="file"
                                                                wire:model.blur="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                                wire:key="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                            />
                                                            <span class="file-cta bg-green has-text-white">
                                                                <x-common.icon
                                                                    name="fas fa-upload"
                                                                    class="file-icon"
                                                                />
                                                                <span class="file-label">
                                                                    Upload {{ $detailPadField->label }}
                                                                </span>
                                                            </span>
                                                            <span class="file-name">
                                                                {{ $details[$loop->parent->index][$detailPadField->id] ?? '' }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div
                                                        class="mt-3"
                                                        wire:loading
                                                        wire:target="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                    >
                                                        <span class="icon text-gold">
                                                            <i class="fas fa-spinner fa-spin"></i>
                                                        </span>
                                                        <span class="text-gold is-uppercase">
                                                            Uploading...
                                                        </span>
                                                    </div>
                                                    <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                </x-forms.field>
                                            </div>
                                        @elseif($detailPadField->isTagTextarea())
                                            <div class="column is-6">
                                                <x-forms.field>
                                                    <x-forms.label for="{{ $loop->parent->index }}{{ $detailPadField->id }}">
                                                        {{ $detailPadField->label }} <sup class="has-text-danger">{{ $detailPadField->isRequired() ? '*' : '' }}</sup>
                                                    </x-forms.label>
                                                    <x-forms.control class="has-icons-left">
                                                        <x-forms.textarea
                                                            id="{{ $loop->parent->index }}{{ $detailPadField->id }}"
                                                            class="pl-6"
                                                            wire:model.blur="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                            wire:key="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                        >
                                                        </x-forms.textarea>
                                                        <x-common.icon
                                                            name="{{ $detailPadField->icon }}"
                                                            class="is-large is-left"
                                                        />
                                                        <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                        @elseif(!$detailPadField->hasRelation() && $detailPadField->isTagSelect())
                                            <div class="column is-6">
                                                <x-forms.field>
                                                    <x-forms.label for="{{ $loop->parent->index }}{{ $detailPadField->id }}">
                                                        {{ $detailPadField->label }} <sup class="has-text-danger">{{ $detailPadField->isRequired() ? '*' : '' }}</sup>
                                                    </x-forms.label>
                                                    <x-forms.control class="has-icons-left">
                                                        <x-forms.select
                                                            id="{{ $loop->parent->index }}{{ $detailPadField->id }}"
                                                            class="is-fullwidth"
                                                            wire:model.blur="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                            wire:key="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                        >
                                                            <option
                                                                selected
                                                                hidden
                                                            >
                                                                Select {{ $detailPadField->label }}
                                                            </option>
                                                            @foreach (explode(',', $detailPadField->tag_type) as $option)
                                                                <option value="{{ $option }}">
                                                                    {{ $option }}
                                                                </option>
                                                            @endforeach
                                                        </x-forms.select>
                                                        <x-common.icon
                                                            name="{{ $detailPadField->icon }}"
                                                            class="is-large is-left"
                                                        />
                                                        <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <x-common.button
                        tag="button"
                        type="button"
                        mode="button"
                        label="Add More Item"
                        class="bg-purple has-text-white is-small ml-3 mt-6"
                        wire:click="addDetail"
                    />
                @endif
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
</div>

@push('scripts')
    <script type="text/javascript">
        window.addEventListener('select2-removed', triggerSelect2Change)

        window.addEventListener("load", triggerSelect2Change);

        function bindData(element, prop) {
            $(element).on('change', function(e) {
                @this.set(prop, $(element).select2("val"));
            });
        }

        function triggerSelect2Change() {
            setTimeout(() => {
                $('.select2-picker').each(function(index, element) {
                    let value = $(this).closest('.control').attr('data-selected-value');

                    $(this).val(value).trigger('change');
                });
            });
        }

        function summerNote(element, property) {
            $(element).summernote({
                placeholder: "Write description or other notes here",
                tabsize: 2,
                minHeight: 90,
                tabDisable: true,
                toolbar: [
                    ["font", ["bold"]],
                    ["table", ["table"]],
                    ["forecolor", ["forecolor"]],
                ],
                callbacks: {
                    onInit: function() {
                        $(element).summernote("code", @this.get(property))
                    },
                    onChange: function(contents, $editable) {
                        @this.set(property, contents);
                    }
                }
            });
        }
    </script>
@endpush

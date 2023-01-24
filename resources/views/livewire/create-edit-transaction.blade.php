<div>
    <x-common.content-wrapper>
        <x-content.header title="{{ isset($transaction) ? 'Edit' : 'New' }} {{ $pad->name }}" />
        <form
            id="formOne"
            method="POST"
            enctype="multipart/form-data"
            novalidate
            wire:submit.prevent="{{ isset($transaction) ? 'update' : 'store' }}"
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
                                    wire:model="code"
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
                                        wire:model="status"
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
                                    wire:model="issued_on"
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
                                                x-init="initSelect2($el, '{{ $masterPadField->padRelation->model_name }}');
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
                        @elseif ($masterPadField->isTagInput() && !$masterPadField->isInputTypeCheckbox() && !$masterPadField->isInputTypeRadio())
                            @continue($masterPadField->label == 'Discount' && userCompany()->isDiscountBeforeTax())
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="{{ $masterPadField->id }}">
                                        {{ $masterPadField->label }} <sup class="has-text-danger">{{ $masterPadField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            type="{{ $masterPadField->tag_type }}"
                                            id="{{ $masterPadField->id }}"
                                            wire:model="master.{{ $masterPadField->id }}"
                                        />
                                        <x-common.icon
                                            name="{{ $masterPadField->icon }}"
                                            class="is-large is-left"
                                        />
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
                        @elseif($masterPadField->isTagSelect())
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="{{ $masterPadField->id }}">
                                        {{ $masterPadField->label }} <sup class="has-text-danger">{{ $masterPadField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="{{ $masterPadField->id }}"
                                            wire:model="master.{{ $masterPadField->id }}"
                                        >
                                            <option
                                                selected
                                                hidden
                                            >
                                                Select Method
                                            </option>
                                            <option value="Cash"> Cash </option>
                                            <option value="Credit"> Credit </option>
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
                                                        />
                                                        <x-common.icon
                                                            name="fas fa-th"
                                                            class="is-large is-left"
                                                        />
                                                        <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                        @elseif ($detailPadField->hasRelation() && $detailPadField->padRelation->model_name != 'Product')
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
                                        @elseif ($detailPadField->isTagInput() && !$detailPadField->isInputTypeFile() && !$detailPadField->isInputTypeCheckbox() && !$detailPadField->isInputTypeRadio())
                                            @continue($detailPadField->label == 'Discount' && !userCompany()->isDiscountBeforeTax())
                                            <div class="column is-6">
                                                <x-forms.field>
                                                    <x-forms.label for="{{ $loop->parent->index }}{{ $detailPadField->id }}">
                                                        {{ $detailPadField->label }}
                                                        <sup class="has-text-danger">
                                                            {{ $detailPadField->isRequired() ? '*' : '' }}
                                                        </sup>
                                                        @if ($detailPadField->label == 'Unit Price')
                                                            <sup class="has-text-weight-light">
                                                                ({{ userCompany()->getPriceMethod() }})
                                                            </sup>
                                                        @endif
                                                    </x-forms.label>
                                                    <x-forms.control class="has-icons-left">
                                                        <x-forms.input
                                                            type="{{ $detailPadField->tag_type }}"
                                                            id="{{ $loop->parent->index }}{{ $detailPadField->id }}"
                                                            wire:model="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
                                                        />
                                                        <x-common.icon
                                                            name="{{ $detailPadField->icon }}"
                                                            class="is-large is-left"
                                                        />
                                                        <x-common.validation-error property="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}" />
                                                    </x-forms.control>
                                                </x-forms.field>
                                            </div>
                                        @elseif ($detailPadField->isTagInput() && $detailPadField->isInputTypeFile())
                                            @continue($detailPadField->label == 'Discount' && !userCompany()->isDiscountBeforeTax())
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
                                                                wire:model="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
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
                                                            wire:model="details.{{ $loop->parent->index }}.{{ $detailPadField->id }}"
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
            $('.select2-picker').each(function(index, element) {
                let value = $(this).closest('.control').attr('data-selected-value');

                $(this).val(value).trigger('change');
            })
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

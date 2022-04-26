<div>
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
                    @foreach ($padFields as $padField)
                        <div class="column is-6">
                            @if ($padField->hasRelation() && $padField->padRelation->model_name == 'Product')
                                <x-forms.label for="{{ $loop->parent->index }}{{ $padField->id }}">
                                    {{ $padField->label }} <sup class="has-text-danger">{{ $padField->isRequired() ? '*' : '' }}</sup>
                                </x-forms.label>
                                <x-forms.field
                                    class="has-addons"
                                    x-data="productDataProvider({{ $detail[$padField->id] ?? '' }})"
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
                                    <x-forms.control class="has-icons-left is-expanded">
                                        <x-common.product-list
                                            tags="false"
                                            name="detail[{{ $loop->parent->index }}][{{ $padField->id }}]"
                                            :selected-product-id="$detail[$padField->id] ?? ''"
                                            x-init="select2"
                                            wire:ignore
                                        />
                                        <x-common.icon
                                            name="fas fa-th"
                                            class="is-large is-left"
                                        />
                                    </x-forms.control>
                                </x-forms.field>
                            @elseif ($padField->hasRelation() && $padField->padRelation->model_name != 'Product')
                                <x-forms.field>
                                    <x-forms.label
                                        for="{{ $loop->parent->index }}{{ $padField->id }}"
                                        class="label text-green has-text-weight-normal"
                                    >
                                        {{ $padField->label }} <sup class="has-text-danger">{{ $padField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="control has-icons-left">
                                        <div
                                            class="select is-fullwidth"
                                            wire:ignore
                                        >
                                            <x-dynamic-component
                                                :component="$padField->padRelation->component_name"
                                                :selected-id="$detail[$padField->id] ?? ''"
                                                name="detail[{{ $loop->parent->index }}][{{ $padField->id }}]"
                                                id="{{ $loop->parent->index }}{{ $padField->id }}"
                                                x-init="initSelect2($el, '{{ $padField->padRelation->model_name }}', 'details.{{ $loop->parent->index }}.{{ $padField->id }}')"
                                            />
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="{{ $padField->icon }}"></i>
                                        </div>
                                    </x-forms.control>
                                </x-forms.field>
                            @elseif ($padField->isTagInput() && !$padField->isInputTypeCheckbox() && !$padField->isInputTypeRadio())
                                <x-forms.field>
                                    <x-forms.label for="detail[{{ $loop->parent->index }}][{{ $padField->id }}]">
                                        {{ $padField->label }} <sup class="has-text-danger">{{ $padField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            type="{{ $padField->tag_type }}"
                                            name="detail[{{ $loop->parent->index }}][{{ $padField->id }}]"
                                            id="detail[{{ $loop->parent->index }}][{{ $padField->id }}]"
                                            wire:model="details.{{ $loop->parent->index }}.{{ $padField->id }}"
                                        />
                                        <x-common.icon
                                            name="{{ $padField->icon }}"
                                            class="is-large is-left"
                                        />
                                    </x-forms.control>
                                </x-forms.field>
                            @elseif($padField->isTagTextarea())
                                <x-forms.field>
                                    <x-forms.label for="detail[{{ $loop->parent->index }}][{{ $padField->id }}]">
                                        {{ $padField->label }} <sup class="has-text-danger">{{ $padField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.textarea
                                            name="detail[{{ $loop->parent->index }}][{{ $padField->id }}]"
                                            id="detail[{{ $loop->parent->index }}][{{ $padField->id }}]"
                                            class="pl-6"
                                            wire:model="details.{{ $loop->parent->index }}.{{ $padField->id }}"
                                        >
                                        </x-forms.textarea>
                                        <x-common.icon
                                            name="{{ $padField->icon }}"
                                            class="is-large is-left"
                                        />
                                    </x-forms.control>
                                </x-forms.field>
                            @endif
                        </div>
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
</div>

@push('scripts')
    <script type="text/javascript">
        function initSelect2(element, label, prop) {
            let select2 = $(element).select2({
                placeholder: `Select ${label}`,
                allowClear: true
            });

            select2.on('change', function(e) {
                let value = select2.select2("val");
                @this.set(prop, value);
            });
        }
    </script>
@endpush

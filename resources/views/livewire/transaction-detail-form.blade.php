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
                            @if ($padField->hasRelation() && $padField->padRelation->model_name != 'Product')
                                <x-forms.field>
                                    <x-forms.label
                                        for="{{ $padField->id }}"
                                        class="label text-green has-text-weight-normal"
                                    >
                                        {{ $padField->label }} <sup class="has-text-danger">{{ $padField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="control has-icons-left">
                                        <div class="select is-fullwidth">
                                            <x-dynamic-component
                                                :component="$padField->padRelation->component_name"
                                                :selected-id="old($padField->id)"
                                                name="detail[{{ $loop->parent->index }}][{{ $padField->id }}]"
                                                id="{{ $loop->parent->index }}{{ $padField->id }}"
                                                wire:model="details.{{ $loop->parent->index }}.{{ $padField->id }}"
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
                                        {{-- <x-common.validation-error property="{{ $padField->id }}" /> --}}
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
                                        {{-- <x-common.validation-error property="{{ $padField->id }}" /> --}}
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

@foreach ($customFields as $customField)
    <div class="column {{ $customField->column_size }}">
        @if ($customField->isTagInput() && in_array($customField->tag_type, ['text', 'number', 'date']))
            <x-forms.field>
                <x-forms.label for="customField[{{ $customField->id }}]">
                    {{ $customField->label }} <sup class="has-text-danger">{{ $customField->isRequired() ? '*' : '' }}</sup>
                </x-forms.label>
                <x-forms.control class="has-icons-left">
                    <x-forms.input
                        type="{{ $customField->tag_type }}"
                        name="customField[{{ $customField->id }}]"
                        id="customField[{{ $customField->id }}]"
                        placeholder="{{ $customField->placeholder }}"
                        value="{{ $input[$customField->id] ?? $customField->default_value }}"
                    />
                    <x-common.icon
                        name="{{ $customField->icon }}"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="customField.{{ $customField->id }}" />
                </x-forms.control>
            </x-forms.field>
        @elseif ($customField->isTagSelect() || ($customField->isTagInput() && in_array($customField->tag_type, ['radio'])))
            <x-forms.field>
                <x-forms.label for="customField[{{ $customField->id }}]">
                    {{ $customField->label }} <sup class="has-text-danger">{{ $customField->isRequired() ? '*' : '' }}</sup>
                </x-forms.label>
                <x-forms.control class="has-icons-left">
                    <x-forms.select
                        class="is-fullwidth"
                        id="customField[{{ $customField->id }}]"
                        name="customField[{{ $customField->id }}]"
                    >
                        <option
                            selected
                            disabled
                        >Select {{ $customField->placeholder ?? $customField->label }}</option>
                        @foreach (explode(',', $customField->options) as $option)
                            <option
                                value="{{ $option }}"
                                @selected($option == ($input[$customField->id] ?? $customField->default_value))
                            >{{ $option }}</option>
                        @endforeach
                    </x-forms.select>
                    <x-common.icon
                        name="{{ $customField->icon }}"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="customField.{{ $customField->id }}" />
                </x-forms.control>
            </x-forms.field>
        @endif
    </div>
@endforeach

@extends('layouts.app')

@section('title', 'Create ' . $pad->name)

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New {{ $pad->name }}" />
        <form
            id="formOne"
            action="{{ route('pads.transactions.store', $pad->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Reference Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $currentReferenceCode }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="issued_on">
                                Issued On <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="datetime-local"
                                    name="issued_on"
                                    id="issued_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('issued_on', now()->toDateTimeLocalString()) }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="issued_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @foreach ($pad->padFields as $padField)
                        @if ($padField->isMasterField() && $padField->hasRelation())
                            <div class="column is-6">
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
                                                name="master[{{ $padField->id }}]"
                                                id="{{ $padField->id }}"
                                            />
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="{{ $padField->icon }}"></i>
                                        </div>
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @elseif ($padField->isMasterField() && $padField->isTagInput() && !$padField->isInputTypeCheckbox() && !$padField->isInputTypeRadio())
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="{{ $padField->id }}">
                                        {{ $padField->label }} <sup class="has-text-danger">{{ $padField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            type="{{ $padField->tag_type }}"
                                            name="master[{{ $padField->id }}]"
                                            id="{{ $padField->id }}"
                                            value="{{ old($padField->id) }}"
                                        />
                                        <x-common.icon
                                            name="{{ $padField->icon }}"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="{{ $padField->id }}" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @elseif($padField->isMasterField() && $padField->isTagTextarea())
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="{{ $padField->id }}">
                                        {{ $padField->label }} <sup class="has-text-danger">{{ $padField->isRequired() ? '*' : '' }}</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.textarea
                                            name="master[{{ $padField->id }}]"
                                            id="{{ $padField->id }}"
                                            class="pl-6"
                                        >
                                            {{ old($padField->id) ?? '' }}
                                        </x-forms.textarea>
                                        <x-common.icon
                                            name="{{ $padField->icon }}"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="{{ $padField->id }}" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endif
                    @endforeach
                </div>
                @if ($pad->hasDetailPadFields())
                    <livewire:transaction-detail-form :pad-id="$pad->id" />
                @endif
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

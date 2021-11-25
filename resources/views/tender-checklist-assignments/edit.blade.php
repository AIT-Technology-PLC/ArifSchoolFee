@extends('layouts.app')

@section('title', 'Checklist Assignments')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Checklist Assignments" />
        <form
            id="formOne"
            action="{{ route('tender-checklists-assignments.update', $tender->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    @foreach ($tender->tenderChecklists as $tenderChecklist)
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label>
                                    Checklist <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        name="checklist[{{ $loop->index }}][id]"
                                        type="hidden"
                                        value="{{ $tenderChecklist->id }}"
                                    />
                                    <x-forms.input
                                        value="{{ $tenderChecklist->generalTenderChecklist->item }}"
                                        disabled
                                    />
                                    <x-common.icon
                                        name="fas fa-check-double"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="checklist.{{ $loop->index }}.id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="checklist[{{ $loop->index }}][assigned_to]">
                                    Assigned To <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="checklist[{{ $loop->index }}][assigned_to]"
                                        name="checklist[{{ $loop->index }}][assigned_to]"
                                    >
                                        <option
                                            disabled
                                            selected
                                            value=""
                                        >
                                            Select Employee
                                        </option>
                                        @foreach ($users as $user)
                                            <option
                                                value="{{ $user->id }}"
                                                {{ $tenderChecklist->assigned_to == $user->id ? 'selected' : '' }}
                                            >
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                        <option value="">None</option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-user"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="checklist.{{ $loop->index }}.assigned_to" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endforeach
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

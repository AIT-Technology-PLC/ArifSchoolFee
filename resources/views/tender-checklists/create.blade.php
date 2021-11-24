@extends('layouts.app')

@section('title')
    Select & Add Checklists
@endsection

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Select & Add Checklists" />
        <form
            id="formOne"
            action="{{ route('tenders.tender-checklists.store', $tender->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                @error('checklists')
                    <x-common.fail-message :message="$message" />
                @enderror

                <p class="mx-3">
                    @if ($totalGeneralTenderChecklists > 0 && $totalGeneralTenderChecklists == $tender->tender_checklists_count)
                        <x-common.success-message message="All checklists are already selected" />
                    @elseif($totalGeneralTenderChecklists == 0)
                        <x-common.fail-message message="No checklists found" />
                    @endif
                </p>

                @foreach ($tenderChecklistTypes as $tenderChecklistType)
                    @continue($tenderChecklistType->generalTenderChecklists->isEmpty())

                    <x-common.content-wrapper>
                        <x-content.header title="{{ $tenderChecklistType->name }}" />
                        <x-content.footer>
                            <div class="columns is-marginless is-multiline">
                                @foreach ($tenderChecklistType->generalTenderChecklists as $generalTenderChecklist)
                                    <div class="column is-one-fifth">
                                        <div class="field">
                                            <div class="control">
                                                <label class="checkbox text-green has-text-weight-normal is-size-7">
                                                    <input
                                                        type="checkbox"
                                                        name="checklists[][general_tender_checklist_id]"
                                                        value="{{ $generalTenderChecklist->id }}"
                                                        {{ old('checklists.*.general_tender_checklist_id') == $generalTenderChecklist->id ? 'checked' : '' }}
                                                    >
                                                    {{ $generalTenderChecklist->item }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </x-content.footer>
                    </x-common.content-wrapper>
                @endforeach
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

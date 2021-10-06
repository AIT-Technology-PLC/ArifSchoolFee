@extends('layouts.app')

@section('title')
    Select & Add Checklists
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Select & Add Checklists
            </h1>
        </div>

        <form id="formOne" action="{{ route('tender-checklists.store', 'tender=' . $tender->id) }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="has-text-right-tablet mb-4">
                    <button id="selectAllCheckboxes" class="button bg-purple is-small has-text-white" type="button">
                        <span class="icon">
                            <i class="fas fa-check-square"></i>
                        </span>
                        <span>
                            Select All
                        </span>
                    </button>
                </div>
                <p class="mx-3">
                    @if ($totalGeneralTenderChecklists > 0 && $totalGeneralTenderChecklists == $tender->tender_checklists_count)
                        <x-success-message message="All checklists are already selected" />
                    @elseif($totalGeneralTenderChecklists == 0)
                        <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
                            <p class="has-text-centered text-purple">
                                <span class="icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </span>
                                <span>
                                    No checklists found
                                </span>
                            </p>
                        </div>
                    @endif
                </p>
                <div class="columns is-marginless is-multiline">
                    @foreach ($generalTenderChecklists as $generalTenderChecklist)
                        <div class="column is-one-fifth">
                            <div class="field">
                                <div class="control">
                                    <label class="checkbox text-green has-text-weight-normal is-size-7">
                                        <input type="checkbox" name="checklists[{{ $loop->index }}][general_tender_checklist_id]" value="{{ $generalTenderChecklist->id }}" {{ old('checklists.' . $loop->index . '.general_tender_checklist_id') == $generalTenderChecklist->id ? 'checked' : '' }}>
                                        {{ $generalTenderChecklist->item }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('checklists')
                    <span class="help has-text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="box radius-top-0">
                <div class="columns is-marginless">
                    <div class="column is-paddingless">
                        <div class="buttons is-right">
                            <button class="button is-white text-green" type="reset">
                                <span class="icon">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span>
                                    Cancel
                                </span>
                            </button>
                            <button id="saveButton" class="button bg-green has-text-white">
                                <span class="icon">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span>
                                    Save
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

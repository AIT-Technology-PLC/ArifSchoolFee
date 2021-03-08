@extends('layouts.app')

@section('title')
    New Tender Checklist
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Tender Checklist
            </h1>
        </div>

        <form id="formOne" action="{{ route('tender-checklists.store', 'tender=' . $tender->id) }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <p class="text-green has-text-weight-normal mx-3 mb-3 mt-4">
                    Select Checklists
                </p>
                <div class="columns is-marginless is-multiline">
                    @foreach ($generalTenderChecklists as $generalTenderChecklist)
                        @if ($tender->tenderChecklists->where('item', $generalTenderChecklist->item)->count())
                            @continue
                        @endif

                        <div class="column is-one-fifth">
                            <div class="field">
                                <div class="control">
                                    <label class="checkbox text-green has-text-weight-normal is-size-7">
                                        <input type="checkbox" name="checklists[{{ $loop->index }}][item]" value="{{ $generalTenderChecklist->item }}" {{ old('checklists.' . $loop->index . '.item') == $generalTenderChecklist->item ? 'checked' : '' }}>
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

@extends('layouts.app')

@section('title')
    Edit General Tender Checklist
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit General Tender Checklist
            </h1>
        </div>
        <form id="formOne" action="{{ route('general-tender-checklists.update', $generalTenderChecklist->id) }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="item" class="label text-green has-text-weight-normal">Item <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="item" name="item" type="text" class="input" placeholder="Checklist Name" value="{{ $generalTenderChecklist->item }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-check"></i>
                                </span>
                                @error('item')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="tender_checklist_type_id" class="label text-green has-text-weight-normal"> Checklist Type <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="tender_checklist_type_id" name="tender_checklist_type_id">
                                        <option selected disabled>Select Checklist Type</option>
                                        @foreach ($tenderChecklistTypes as $tenderChecklistType)
                                            <option value="{{ $tenderChecklistType->id }}" {{ $generalTenderChecklist->tender_checklist_type_id == $tenderChecklistType->id ? 'selected' : '' }}>{{ $tenderChecklistType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                @error('tender_checklist_type_id')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="description" class="label text-green has-text-weight-normal">Description</label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note about this checklist">{{ $generalTenderChecklist->description }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-edit"></i>
                                </span>
                                @error('description')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box radius-top-0">
                <x-save-button />
            </div>
        </form>
    </section>
@endsection

@extends('layouts.app')

@section('title')
    Update Tender Checklist
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Update Tender Checklist
            </h1>
        </div>
        <form id="formOne" action="{{ route('tender-checklists.update', $tenderChecklist->id) }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label class="label text-green has-text-weight-normal">Item <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input name="item" type="text" class="input" value="{{ $tenderChecklist->item }}" disabled>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-tasks"></i>
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
                            <label class="label text-green has-text-weight-normal">Status <sup class="has-text-danger">*</sup> </label>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="status" value="Completed" {{ $tenderChecklist->status == 'Completed' ? 'checked' : '' }}>
                                    Completed
                                </label>
                                <label class="radio">
                                    <input type="radio" name="status" value="Not Yet" {{ $tenderChecklist->status == 'Not Yet' ? 'checked' : '' }}>
                                    Not Yet
                                </label>
                                @error('status')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="comment" class="label text-green has-text-weight-normal">Comment</label>
                            <div class="control has-icons-left">
                                <textarea name="comment" id="comment" cols="30" rows="3" class="textarea pl-6" placeholder="Comment or note about this checklist">{{ $tenderChecklist->comment }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-edit"></i>
                                </span>
                                @error('comment')
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

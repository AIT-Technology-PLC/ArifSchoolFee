@extends('layouts.app')

@section('title')
    Tender Readings
@endsection

@section('content')

    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Tender Readings
            </h1>
        </div>
        <form id="formOne" action="{{ route('tender-readings.update', $tender->id) }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <div class="field">
                            <label for="financial_reading" class="label text-green has-text-weight-normal">Financial Reading <sup class="has-text-danger"></sup> </label>
                            <div class="control">
                                <textarea name="financial_reading" id="financial_reading" cols="30" rows="5" class="summernote textarea" placeholder="">{{ $tender->financial_reading ?? '' }}</textarea>
                                @error('financial_reading')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="field">
                            <label for="technical_reading" class="label text-green has-text-weight-normal">Technical Reading <sup class="has-text-danger"></sup> </label>
                            <div class="control">
                                <textarea name="technical_reading" id="technical_reading" cols="30" rows="5" class="summernote textarea" placeholder="">{{ $tender->technical_reading ?? '' }}</textarea>
                                @error('technical_reading')
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

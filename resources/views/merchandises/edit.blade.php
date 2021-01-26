@extends('layouts.app')

@section('title')
    Manage Returns, Damage and Transfers
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Manage Returns and Damage
            </h1>
        </div>
        <form id="formOne" action="{{ route('merchandises.update', $merchandise->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <label for="total_broken" class="label text-green has-text-weight-normal">Add Products Damaged/Broken  <sup class="has-text-danger"></sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="total_broken" name="total_broken" type="number" class="input" value="{{ $merchandise->total_broken ?? '0.00' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                                @error('total_broken')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="control">
                                <button class="button bg-green has-text-white" type="button">
                                    {{ $merchandise->product->unit_of_measurement }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <label for="total_returns" class="label text-green has-text-weight-normal">Add Products Returned  <sup class="has-text-danger"></sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="total_returns" name="total_returns" type="number" class="input" value="{{ $merchandise->total_returns ?? '0.00' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                                @error('total_returns')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="control">
                                <button class="button bg-green has-text-white" type="button">
                                    {{ $merchandise->product->unit_of_measurement }}
                                </button>
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

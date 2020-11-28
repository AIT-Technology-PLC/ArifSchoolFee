@extends('layouts.app')

@section('title')
    Edit Settings
@endsection

@section('content')
    @include('components.previous_url')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit General Settings
            </h1>
        </div>
        <form action="{{ route('companies.update', auth()->user()->employee->company_id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="name" class="label text-green has-text-weight-normal">Name <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="name" name="name" type="text" class="input" placeholder="Company Name" value="{{ $company->name }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-building"></i>
                                </span>
                                @error('name')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="sector" class="label text-green has-text-weight-normal"> Business Sector <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="sector" name="sector">
                                        <option selected disabled>Select Sector</option>
                                        <option value="Manufacturer" {{ $company->sector == 'Manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                                        <option value="Wholesaler" {{ $company->sector == 'Wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                                        <option value="Processor" {{ $company->sector == 'Processor' ? 'selected' : '' }}>Processor</option>
                                        <option value="Retailer" {{ $company->sector == 'Retailer' ? 'selected' : '' }}>Retailer</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-city"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="currency" class="label text-green has-text-weight-normal"> Currency <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="currency" name="currency">
                                        <option selected disabled>Select Currency</option>
                                        <option value="AED" {{ $company->currency == 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                                        <option value="CHF" {{ $company->currency == 'CHF' ? 'selected' : '' }}>CHF - Swiss Frank</option>
                                        <option value="CNY" {{ $company->currency == 'CNY' ? 'selected' : '' }}>CNY - China Yuan</option>
                                        <option value="ETB" {{ $company->currency == 'ETB' ? 'selected' : '' }}>ETB - Ethiopian Birr</option>
                                        <option value="EUR" {{ $company->currency == 'EUR' ? 'selected' : '' }}>EUR - Euro Union Countries</option>
                                        <option value="GBP" {{ $company->currency == 'GBP' ? 'selected' : '' }}>GBP - GB Pound Sterling</option>
                                        <option value="SAR" {{ $company->currency == 'SAR' ? 'selected' : '' }}>SAR - Saudi Riyal</option>
                                        <option value="USD" {{ $company->currency == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
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
                            <button class="button bg-green has-text-white">
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

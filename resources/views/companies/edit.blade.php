@extends('layouts.app')

@section('title')
Edit Settings
@endsection

@section('content')
<section class="mt-3 mx-3 m-lr-0">
    <div class="box radius-bottom-0 mb-0 has-background-white-bis">
        <h1 class="title text-green has-text-weight-medium is-size-5">
            Edit General Settings
        </h1>
    </div>
    <form action="{{ route('companies.update', auth()->user()->employee->company_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="name" class="label text-green has-text-weight-normal">Name <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <input id="name" name="name" type="text" class="input" placeholder="Employee Name" value="{{ $company->name }}" autocomplete="name" autofocus>
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
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
                        <label for="inventory" class="label text-green has-text-weight-normal"> Business Sector <sup class="has-text-danger"></sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="inventory" name="inventory">
                                    <option selected disabled>Type</option>
                                    <option>Manufacturer</option>
                                    <option>Wholesaler</option>
                                    <option>Processor</option>
                                    <option>Retailer</option>
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-sitemap"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="inventory" class="label text-green has-text-weight-normal"> Default Currency <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="inventory" name="inventory">
                                    <option selected disabled>Type</option>
                                    <option>Manufacturer</option>
                                    <option>Wholesaler</option>
                                    <option>Processor</option>
                                    <option>Retailer</option>
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-sitemap"></i>
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

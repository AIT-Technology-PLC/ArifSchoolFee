@extends('layouts.app')

@section('title')
    Create New Supplier
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Supplier
            </h1>
        </div>
        <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="company_name" class="label text-green has-text-weight-normal">Company Name <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="company_name" name="company_name" type="text" class="input" placeholder="Company Name" value="{{ old('company_name') ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-building"></i>
                                </span>
                                @error('company_name')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="contact_name" class="label text-green has-text-weight-normal">Contact Name <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="contact_name" name="contact_name" type="text" class="input" placeholder="Contact Name" value="{{ old('contact_name') ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-address-book"></i>
                                </span>
                                @error('contact_name')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="email" class="label text-green has-text-weight-normal">Email <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="email" name="email" type="text" class="input" placeholder="Email Address" value="{{ old('email') ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-at"></i>
                                </span>
                                @error('email')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="phone" class="label text-green has-text-weight-normal">Phone <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="phone" name="phone" type="text" class="input" placeholder="Phone/Telephone" value="{{ old('phone') ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-phone"></i>
                                </span>
                                @error('phone')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="country" class="label text-green has-text-weight-normal"> Country <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="country" name="country">
                                        <option selected disabled>Country</option>
                                        <option value="China" {{ old('country') == 'China' ? 'selected' : '' }}>China</option>
                                        <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                                        <option value="UAE" {{ old('country') == 'UAE' ? 'selected' : '' }}>UAE</option>
                                        <option value="KSA" {{ old('country') == 'KSA' ? 'selected' : '' }}>KSA</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-globe"></i>
                                </div>
                                @error('country')
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

@extends('layouts.app')

@section('title')
    Password Reset
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-blue has-text-weight-medium is-size-5">
                Password Reset
            </h1>
        </div>
        <form 
            id="formOne" 
            action="{{ route('password.update') }}" 
            method="post" 
            enctype="multipart/form-data" 
            novalidate
        >
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <x-common.success-message :message="session('successMessage')" />
                <div class="columns is-marginless is-multiline is-centered">
                    <div class="column is-5">
                        <div class="field">
                            <label 
                                for="password" 
                                class="label text-blue has-text-weight-normal"
                            >
                                New Password <sup class="has-text-danger">*</sup> 
                            </label>
                            <div class="control">
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    class="input" 
                                    placeholder="********" 
                                    autofocus
                                >
                                @error('password')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="field">
                            <label 
                                for="password-confirm" 
                                class="label text-blue has-text-weight-normal"
                            >
                                Confirm New Password<sup class="has-text-danger">*</sup> 
                            </label>
                            <div class="control">
                                <input 
                                    id="password-confirm" 
                                    name="password_confirmation" 
                                    type="password" 
                                    class="input" 
                                    placeholder="********"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box radius-top-0">
                <div class="columns is-marginless">
                    <div class="column is-paddingless">
                        <div class="buttons is-centered">
                            <button class="button is-white text-blue" type="reset">
                                <span class="icon">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span>
                                    Cancel
                                </span>
                            </button>
                            <button id="saveButton" class="button bg-softblue has-text-white">
                                <span class="icon">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span>
                                    Change Password
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@extends('auth.layout.auth')

@section('title')
    Recover Password - AIT
@endsection

@section('content')
    <section class="hero is-fullheight interactive-bg">
        <div class="hero-body">
                <div class="container">
                    <div class="columns is-marginless is-centered">
                        <div class="login column is-4">
                            <section class="section">
                                <form
                                        id="formOne"
                                        method="POST"
                                        action="{{ route('forget.update') }}"
                                        novalidate
                                        autocomplete="off"
                                    >
                                        @csrf
                                        <div class="has-text-centered">
                                            <img class="login-logo" src="{{ asset('img/Ethiopian_Citizens_for_Social_Justice_logo.png')}}">
                                        </div>

                                        <x-common.success-message :message="session('successMessage')" />
                                        <x-common.fail-message :message="session('failedMessage')" />
                                        
                                        <input type="hidden" id="password" name="token" value="{{ $token }}">

                                        <div class="field" for="password">
                                            <label class="label">Password</label>
                                            <div class="control has-icons-right">
                                                <input 
                                                    id="password"
                                                    name="password"
                                                    class="input @error('password') is-danger @enderror" 
                                                    type="password"
                                                    placeholder="********"
                                                    autocomplete="current-password"
                                                    autofocus
                                                    >
                                                <span class="icon is-small is-right">
                                                    <i class="fa fa-key"></i>
                                                </span>
                                                @error('password')
                                                    <span class="help has-text-white">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="field" for="password-confirm">
                                            <label class="label">Confirm New Password</label>
                                            <div class="control has-icons-right">
                                                <input 
                                                    id="password-confirm"
                                                    name="password_confirmation"
                                                    class="input @error('password-confirm') is-danger @enderror" 
                                                    type="password"
                                                    placeholder="********"
                                                    autocomplete="current-password"
                                                    >
                                                <span class="icon is-small is-right">
                                                    <i class="fa fa-key"></i>
                                                </span>
                                                @error('password-confirm')
                                                    <span class="help has-text-white">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="field mt-6">
                                            <div class="control">
                                                <button
                                                    id="saveButton"
                                                    type="submit"
                                                    class="button has-text-white bg-blue is-fullwidth is-size-6 has-text-weight-semibold py-3 px-3"
                                                >
                                                    <span class="icon">
                                                        <i class="fas fa-save"></i>
                                                    </span>
                                                    <span>
                                                        Confirm Password
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                </form>
                            </section>
                            <div class="has-text-centered is-size-7">
                                <p class="has-text-grey">
                                    Copyright © {{ now()->year }} Powered by AIT Technology.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </section>
@endsection

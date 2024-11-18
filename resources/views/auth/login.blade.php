@extends('auth.layout.auth')

@section('title')
    Login
@endsection

@section('content')
        <div class="columns is-marginless">
            <div class="login column is-4">
                <section class="section pt-0">
                    <form
                            id="formOne"
                            method="POST"
                            action="{{ route('post.login') }}"
                            novalidate
                            autocomplete="off"
                        >
                            @csrf
                            <div class="has-text-centered">
                                <img class="login-logo" src="img/AIT LOGO.png">
                            </div>

                            <x-common.success-message :message="session('successMessage')" />

                            <div class="field" for="email">
                                <label class="label">Email*</label>
                                <div class="control has-icons-right">
                                    <input 
                                        id="email"
                                        name="email"
                                        type="text"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        placeholder="Enter User-Name"
                                        class="input"
                                        >
                                    <span class="icon is-small is-right">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <x-common.validation-error property="email" />
                                </div>
                            </div>

                            <div class="field" for="password">
                                <label class="label">Password*</label>
                                <div class="control has-icons-right">
                                    <input 
                                        id="password"
                                        name="password"
                                        class="input" 
                                        type="password"
                                        placeholder="Enter Password"
                                        autocomplete="current-password"
                                        >
                                    <span class="icon is-small is-right">
                                        <i class="fa fa-key"></i>
                                    </span>
                                    <x-common.validation-error property="password" />
                                </div>
                            </div>

                            <div class="has-text-right has-text-weight-light">
                                <a href="{{ route('forget.index') }}">Forget Password ?</a>
                            </div>

                            <div class="field mt-6">
                                <div class="control">
                                    <button
                                        id="saveButton"
                                        type="submit"
                                        class="button has-text-white bg-softblue is-fullwidth is-uppercase is-size-6 has-text-weight-semibold py-3 px-3"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <span>
                                            Login
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
            <div id="particles-js" class="interactive-bg column is-8">
            </div>
        </div>
@endsection

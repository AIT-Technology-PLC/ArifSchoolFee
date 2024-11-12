@extends('auth.layout.auth')

@section('title')
    Sign up - AIT
@endsection

@section('content')
    <section class="hero is-fullheight bg-green limit-to-100vh">
        <div class="hero-head">
            <div class="container">
                <div class="columns is-marginless is-centered">
                    <div class="column is-5">
                        <div class="has-text-centered">
                            <h1 class="has-text-white has-text-weight-light is-size-2 is-size-3-mobile mb-4">
                                Ait<span class="has-text-weight-bold">SchoolPayment&trade;</span>
                            </h1>
                        </div>
                        <div
                            class="box"
                            style="background: #509270;"
                        >
                            <form
                                method="POST"
                                action="{{ route('register') }}"
                                novalidate
                            >
                                @csrf
                                <div class="field">
                                    <label
                                        for="company_name"
                                        class="label has-text-white is-uppercase is-size-7"
                                    > School Name </label>
                                    <div class="control">
                                        <input
                                            id="company_name"
                                            type="text"
                                            class="input bg-lightgreen @error('company_name') is-danger @enderror"
                                            name="company_name"
                                            value="{{ old('company_name') }}"
                                            autocomplete="company_name"
                                            autofocus
                                        >
                                        @error('company_name')
                                            <span
                                                class="help has-text-white"
                                                role="alert"
                                            >
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="field mt-5">
                                    <label
                                        for="name"
                                        class="label has-text-white is-uppercase is-size-7"
                                    > Your Name </label>
                                    <div class="control">
                                        <input
                                            id="name"
                                            type="text"
                                            class="input bg-lightgreen @error('name') is-danger @enderror"
                                            name="name"
                                            value="{{ old('name') }}"
                                            autocomplete="name"
                                            autofocus
                                        >
                                        @error('name')
                                            <span
                                                class="help has-text-white"
                                                role="alert"
                                            >
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="field mt-5">
                                    <label
                                        for="email"
                                        class="label has-text-white is-uppercase is-size-7"
                                    > Email Address </label>
                                    <div class="control">
                                        <input
                                            id="email"
                                            type="email"
                                            class="input bg-lightgreen @error('email') is-danger @enderror"
                                            name="email"
                                            value="{{ old('email') }}"
                                            autocomplete="email"
                                        >
                                        @error('email')
                                            <span
                                                class="help has-text-white"
                                                role="alert"
                                            >
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="field mt-5">
                                    <label
                                        for="password"
                                        class="label has-text-white is-uppercase is-size-7"
                                    > Password </label>
                                    <div class="control">
                                        <input
                                            id="password"
                                            type="password"
                                            class="input bg-lightgreen @error('password') is-danger @enderror"
                                            name="password"
                                            autocomplete="new-password"
                                        >
                                        @error('password')
                                            <span
                                                class="help has-text-white"
                                                role="alert"
                                            >
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="field mt-5">
                                    <label
                                        for="password-confirm"
                                        class="label has-text-white is-uppercase is-size-7"
                                    > Confirm Password </label>
                                    <div class="control">
                                        <input
                                            id="password-confirm"
                                            type="password"
                                            class="input bg-lightgreen"
                                            name="password_confirmation"
                                            autocomplete="new-password"
                                        >
                                    </div>
                                </div>
                                <div class="field mt-6 has-text-centered">
                                    <div class="control">
                                        <button
                                            type="submit"
                                            class="button has-text-white bg-blue is-fullwidth is-uppercase is-size-7 has-text-weight-semibold py-5 px-5"
                                        >
                                            Register
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-foot has-text-centered">
            <img
                src="{{ asset('img/logo.webp') }}"
                width="150"
            >
        </div>
    </section>
@endsection

@extends('auth.layout.auth')

@section('title')
    Login - Onrica
@endsection

@section('content')
    <section class="hero is-fullheight bg-green limit-to-100vh">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-marginless is-centered">
                    <div class="column is-5">
                        <div class="has-text-centered">
                            <h1 class="has-text-white has-text-weight-light is-size-2 mb-4 swera-demo-font">
                                ONRICA
                            </h1>
                        </div>
                        <div
                            class="box"
                            style="background: #509270;"
                        >
                            <form
                                id="formOne"
                                method="POST"
                                action="{{ route('post.login') }}"
                                novalidate
                                autocomplete="off"
                            >
                                @csrf
                                <div class="field">
                                    <label
                                        for="email"
                                        class="label has-text-white is-uppercase is-size-7"
                                    >E-Mail Address</label>
                                    <div class="control">
                                        <input
                                            id="email"
                                            type="email"
                                            class="input bg-lightgreen @error('email') is-danger @enderror"
                                            name="email"
                                            value="{{ old('email') }}"
                                            autocomplete="email"
                                            autofocus
                                        >
                                        @error('email')
                                            <span class="help has-text-white">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="field mt-5">
                                    <label
                                        for="password"
                                        class="label has-text-white is-uppercase is-size-7"
                                    >Password</label>
                                    <div class="control">
                                        <input
                                            id="password"
                                            type="password"
                                            class="input bg-lightgreen @error('password') is-danger @enderror"
                                            name="password"
                                            autocomplete="current-password"
                                        >
                                        @error('password')
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
                                            class="button has-text-white bg-blue is-fullwidth is-uppercase is-size-6 has-text-weight-semibold py-5 px-5"
                                        >
                                            <span class="icon">
                                                <i class="fas fa-sign-in-alt"></i>
                                            </span>
                                            <span>
                                                Login
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

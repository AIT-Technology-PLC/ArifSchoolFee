@extends('auth.layout.auth')

@section('title')
Login - SmartWork by Onrica
@endsection

@section('content')
<section class="hero is-fullheight bg-green limit-to-100vh">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-marginless is-centered">
                <div class="column is-5">
                    <div class="has-text-centered">
                        <img src="{{ asset('img/logo.png') }}" width="200">
                    </div>
                    <div class="box" style="background: #509270;">
                        <form method="POST" action="{{ route('login') }}" novalidate>
                            @csrf
                            <div class="field">
                                <label for="email" class="label has-text-white is-uppercase is-size-7">E-Mail Address</label>
                                <div class="control">
                                    <input id="email" type="email" class="input bg-lightgreen @error('email') is-danger @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="help has-text-white">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="field mt-5">
                                <label for="password" class="label has-text-white is-uppercase is-size-7">Password</label>
                                <div class="control">
                                    <input id="password" type="password" class="input bg-lightgreen @error('password') is-danger @enderror" name="password" autocomplete="current-password">
                                    @error('password')
                                    <span class="help has-text-white">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="field mt-5">
                                <div class="control">
                                    <label class="radio has-text-white">
                                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button has-text-white bg-blue is-fullwidth is-uppercase is-size-7 has-text-weight-semibold py-5 px-5">
                                        Login
                                    </button>
                                    @if (Route::has('password.request'))
                                    <a class="help has-text-white mt-5" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                                    @endif
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

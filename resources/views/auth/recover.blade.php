@extends('auth.layout.auth')

@section('title')
    Recover Password - AIT
@endsection

@section('content')
        <div class="columns is-marginless">
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
                                <img class="login-logo" src="{{ asset('img/AIT LOGO.png')}}">
                            </div>

                            <x-common.success-message :message="session('successMessage')" />
                            <x-common.fail-message :message="session('failedMessage')" />

                            <div class="field" for="email">
                                <label class="label">Email*</label>
                                <div class="control has-icons-right">
                                    <input 
                                        id="email"
                                        name="email"
                                        type="text"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        placeholder="Enter Email Address"
                                        class="input"
                                        >
                                    <span class="icon is-small is-right">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                    <x-common.validation-error property="email" />
                                </div>
                            </div>

                            <div class="field mt-6">
                                <div class="control">
                                    <button id="saveButton" 
                                            type="submit" 
                                            class="button has-text-white bg-blue is-fullwidth is-uppercase is-size-6 has-text-weight-semibold">
                                        <span class="icon">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                        <span>Next</span>
                                    </button>
                                </div>
                            </div>
                            <div class="field mt-4">
                                <div class="control">
                                    <button id="backButton" 
                                            type="button" 
                                            class="button has-text-white bg-blue is-fullwidth is-uppercase is-size-6 has-text-weight-semibold">
                                        <span class="icon">
                                            <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span>Back</span>
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

        <script>
            document.getElementById("backButton").addEventListener("click", function() {
            window.history.back();
        });
</script>
@endsection

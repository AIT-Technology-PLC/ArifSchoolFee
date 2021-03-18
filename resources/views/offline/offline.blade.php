<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> No Internet Connection </title>
    @include('assets.css')
    @include("pwa.tags")
</head>

<body>
    <main>
        <section class="hero bg-lightgreen is-fullheight">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title has-text-grey-lighter is-size-1" style="font-size: 150px !important;">
                        <span class="icon is-large">
                            <i class="fas fa-wifi"></i>
                        </span>
                        <span class="icon is-large" style="margin-left: -85px !important">
                            <i class="fas fa-slash"></i>
                        </span>
                    </h1>
                    <h1 class="title text-green is-size-4-mobile">
                        <span>
                            No Internet Connection
                        </span>
                    </h1>
                    <h2 class="subtitle has-text-grey-light has-text-weight-normal is-size-5-mobile">
                        Please check your internet connection and try again.
                    </h2>
                    <div class="buttons is-centered mt-6">
                        <button id="backButton" class="button btn-green is-outlined is-uppercase has-text-weight-medium px-5 py-5">
                            <span class="icon">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            <span>
                                Back
                            </span>
                        </button>
                        <button id="refreshButton" class="button btn-green is-outlined is-uppercase has-text-weight-medium px-5 py-5">
                            <span class="icon">
                                <i class="fas fa-redo-alt"></i>
                            </span>
                            <span>
                                Try Again
                            </span>
                        </button>
                        <a href="/" class="button bg-green has-text-white is-uppercase has-text-weight-medium px-5 py-5">
                            <span class="icon">
                                <i class="fas fa-bars"></i>
                            </span>
                            <span>
                                Menu
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('assets.js')
</body>

</html>

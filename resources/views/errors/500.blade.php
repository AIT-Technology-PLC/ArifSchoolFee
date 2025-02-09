<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Internal Server Error </title>
    @include('assets.css')
    @include("pwa.tags")
</head>

<body>
    <main>
        <section class="hero bg-lightgreen is-fullheight">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title has-text-grey-lighter is-size-1" style="font-size: 150px !important;">
                        5<span class="text-softblue">0</span>0
                    </h1>
                    <h1 class="title text-softblue">
                        <span>
                           Sorry, we are having a problem with this feature.
                        </span>
                    </h1>
                    <h2 class="subtitle has-text-grey-light has-text-weight-normal">
                        We will have things fixed in no time. 
                        <br>
                        If you need help while the feature is down, please
                        call the helpdesk at +251 97-226-5151.
                    </h2>
                    <button x-data class="button btn-blue is-outlined is-uppercase has-text-weight-medium px-5 py-5" @click="history.back()">
                        <span class="icon">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                        <span>
                            Go Back
                        </span>
                    </button>
                </div>
            </div>
        </section>
    </main>
    @include('assets.js')
</body>

</html>

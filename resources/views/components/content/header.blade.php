@props(['title'])

<div class="box radius-bottom-0 mb-0 has-background-white-bis">
    <h1 class="title text-green has-text-weight-medium is-size-5 is-size-6-mobile">
        <div class="level">
            <div class="level-left">
                <div class="level-item is-justify-content-left">
                    <div>
                        @if (isset($title))
                            <h1 class="title text-green has-text-weight-medium is-size-5">
                                {{ $title }}
                            </h1>
                        @else
                            {{ $header }}
                        @endif
                    </div>
                </div>
            </div>
            @if (strlen($slot))
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </h1>
</div>

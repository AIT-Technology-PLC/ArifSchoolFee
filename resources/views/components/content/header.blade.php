@props(['title', 'isMobile' => false, 'bgColor' => 'has-background-white-bis'])

<div class="box radius-bottom-0 mb-0 {{ $bgColor }} p-4">
    <h1 class="title text-softblue has-text-weight-medium is-size-5 is-size-6-mobile">
        <div class="level {{ $isMobile ? 'is-mobile' : '' }}">
            <div class="level-left">
                <div class="level-item is-justify-content-left">
                    <div>
                        @if (isset($title))
                            <h1 class="title text-softblue has-text-weight-medium is-size-6">
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

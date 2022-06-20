@props(['title', 'action'])

<div
    x-data="toggler"
    @open-create-job-extra-modal.window="toggle"
    class="modal is-active is-invisible"
    x-bind:class="{ 'is-invisible': isHidden }"
>
    <div
        class="modal-background"
        @click="toggle"
    ></div>
    <div class="modal-content p-lr-20">
        <x-common.content-wrapper>
            <x-content.header title="{{ $title }}" />
            <form
                id="create-job-extra"
                action="{{ $action }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                @include('job-extras.extra-details-form', ['data' => session()->getOldInput()])
                <x-content.footer>
                    <x-common.save-button />
                </x-content.footer>
            </form>
        </x-common.content-wrapper>
    </div>
    <x-common.button
        tag="button"
        class="modal-close is-large"
        @click="toggle"
    />
</div>

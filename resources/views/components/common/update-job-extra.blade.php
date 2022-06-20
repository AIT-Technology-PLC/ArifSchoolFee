@props(['title', 'action', 'jobExtras'])

<div
    x-data="toggler"
    @open-update-job-extra-modal.window="toggle"
    class="modal is-active is-invisible"
    x-bind:class="{ 'is-invisible': isHidden }"
>
    <div
        class="modal-background"
        @click="toggle"
    ></div>
    <div class="modal-content p-lr-20">
        <x-common.content-wrapper>
            <x-content.header title="update job Extra" />
            <form
                id="update-job-extra"
                action="{{ $action }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    <div class="columns is-marginless is-multiline">
                        @include('jobs.extra-details-form', ['data' => ['job' => $jobExtra]])
                    </div>
                </x-content.main>
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

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
            <x-content.header title="Update Extra Materials" />
            <form
                id="create-job-extra"
                action="{{ route('jobs.job-extras.store', $job->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf

                @include('job-extras.partials.details-form', ['data' => session()->getOldInput()])

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

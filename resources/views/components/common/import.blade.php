@props(['title', 'action', 'button' => 'open-import-modal'])

<div
    x-data="toggler"
    {{ '@' . $button }}.window="toggle"
    class="modal is-active"
    x-cloak
    x-show="!isHidden"
    x-transition
>
    <div
        class="modal-background"
        @click="toggle"
    ></div>
    <div class="modal-content p-lr-20">
        <x-common.content-wrapper>
            <x-content.header>
                <x-slot name="header">
                    <h1 class="title text-softblue has-text-weight-medium is-size-5">
                        <x-common.icon name="fas fa-heading" />
                        - {{ $title }}
                    </h1>
                </x-slot>
                <button class="delete" aria-label="close"  @click="toggle"></button>
            </x-content.header>
            <form
                id="formOne"
                action="{{ $action }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
                x-data="UploadedFileNameHandler"
                x-on:reset="remove"
            >
                @csrf
                <x-content.main>
                    <div class="field">
                        <div class="file has-name">
                            <label class="file-label">
                                <input
                                    class="file-input"
                                    type="file"
                                    name="file"
                                    x-model="file"
                                    x-on:change="getFileName"
                                >
                                <span class="file-cta bg-softblue has-text-white">
                                    <span class="file-icon">
                                        <i class="fas fa-upload"></i>
                                    </span>
                                    <span class="file-label">
                                        Upload file
                                    </span>
                                </span>
                                <span
                                    class="file-name"
                                    x-text="fileName || 'Select File...'"
                                >
                                </span>
                            </label>
                        </div>
                    </div>
                </x-content.main>
                <x-content.footer>
                    <x-common.save-button />
                </x-content.footer>
            </form>
        </x-common.content-wrapper>
    </div>
</div>

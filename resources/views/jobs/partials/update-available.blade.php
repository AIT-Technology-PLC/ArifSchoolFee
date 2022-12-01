<div
    x-data="toggler"
    @open-update-available-modal.window="toggle"
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
            <x-content.header title="Finished Goods - New Update" />
            <form
                id="job-to-available"
                action="{{ route('jobs.add_to_available', $job->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    @foreach ($jobDetails as $jobDetail)
                        @if (!$jobDetail->isCompleted())
                            <div class="columns is-marginless is-multiline">
                                <div class="column is-3">
                                    <x-forms.field>
                                        <x-forms.label class="is-size-7">
                                            Product <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.select
                                                class="is-fullwidth"
                                                name="job[{{ $loop->index }}][product_id]"
                                                readonly
                                            >
                                                <option value="{{ $jobDetail->product_id }}">{{ $jobDetail->product->name }}</option>
                                            </x-forms.select>
                                            <x-common.icon
                                                name="fas fa-th"
                                                class="is-large is-left"
                                            />
                                            <x-common.validation-error property="job.{{ $loop->index }}.product_id" />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-3">
                                    <x-forms.field>
                                        <x-forms.label class="is-size-7">
                                            Quantity <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                type="number"
                                                disabled
                                                value="{{ $jobDetail->quantity }}"
                                            />
                                            <x-common.icon
                                                name="fas fa-balance-scale"
                                                class="is-large is-left"
                                            />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-3">
                                    <x-forms.field>
                                        <x-forms.label class="is-size-7">
                                            Work in Process <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                type="number"
                                                disabled
                                                value="{{ $jobDetail->wip }}"
                                            />
                                            <x-common.icon
                                                name="fas fa-balance-scale"
                                                class="is-large is-left"
                                            />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-3">
                                    <x-forms.field>
                                        <x-forms.label
                                            for="job[{{ $loop->index }}][available]"
                                            class="is-size-7"
                                        >
                                            Finished Goods <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.input
                                                type="number"
                                                name="job[{{ $loop->index }}][available]"
                                                id="job[{{ $loop->index }}][available]"
                                                value="0"
                                            />
                                            <x-common.icon
                                                name="fas fa-balance-scale"
                                                class="is-large is-left"
                                            />
                                            <x-common.validation-error property="job[{{ $loop->index }}][available]" />
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                            </div>
                        @endif
                    @endforeach
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

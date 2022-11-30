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
                <x-content.main x-data="{ limit: 0 }">
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
                                                x-on:change="limit=$el.value"
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
                            <div
                                x-data="chassisTrackerMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                                x-init="$store.errors.setErrors({{ Js::from($errors->get('chassisTracker.*')) }})"
                            >
                                @if (userCompany()->allowChassisTracker() && $jobDetail->product->hasChassisTracker())
                                    <template
                                        x-for="(chassisTracker, index) in chassisTrackers"
                                        x-bind:key="index"
                                    >
                                        <div class="mx-3">
                                            <x-forms.field class="has-addons mb-0 mt-5">
                                                <x-forms.control>
                                                    <span
                                                        class="tag bg-green has-text-white is-medium is-radiusless"
                                                        x-text="`Chassis ${index + 1}`"
                                                    ></span>
                                                </x-forms.control>
                                                <x-forms.control>
                                                    <x-common.button
                                                        tag="button"
                                                        mode="tag"
                                                        type="button"
                                                        class="bg-lightgreen has-text-white is-medium is-radiusless"
                                                        x-on:click="remove(index)"
                                                    >
                                                        <x-common.icon
                                                            name="fas fa-times-circle"
                                                            class="text-green"
                                                        />
                                                    </x-common.button>
                                                </x-forms.control>
                                            </x-forms.field>
                                            <div class="box has-background-white-bis radius-top-0">
                                                <div class="columns is-marginless is-multiline">
                                                    <div class="column is-6">
                                                        <x-forms.label x-bind:for="`job[{{ $loop->index }}][chassisTracker][${index}][chassis_number]`">
                                                            Chassis Number <sup class="has-text-danger">*</sup>
                                                        </x-forms.label>
                                                        <x-forms.field class="has-addons">
                                                            <x-forms.control class="has-icons-left is-expanded">
                                                                <x-forms.input
                                                                    type="text"
                                                                    x-bind:id="`job[{{ $loop->index }}][chassisTracker][${index}][chassis_number]`"
                                                                    x-bind:name="`job[{{ $loop->index }}][chassisTracker][${index}][chassis_number]`"
                                                                    x-model="chassisTracker.chassis_number"
                                                                    placeholder="Chassis Number"
                                                                />
                                                                <x-common.icon
                                                                    name="fas fa-th"
                                                                    class="is-small is-left"
                                                                />
                                                                <span
                                                                    class="help has-text-danger"
                                                                    x-text="$store.errors.getErrors(`job[{{ $loop->index }}][chassisTracker].${index}.chassis_number`)"
                                                                ></span>
                                                            </x-forms.control>
                                                        </x-forms.field>
                                                    </div>
                                                    <div class="column is-6">
                                                        <x-forms.label x-bind:for="`job[{{ $loop->index }}][chassisTracker][${index}][engine_number]`">
                                                            Engine Number <sup class="has-text-danger">*</sup>
                                                        </x-forms.label>
                                                        <x-forms.field class="has-addons">
                                                            <x-forms.control class="has-icons-left is-expanded">
                                                                <x-forms.input
                                                                    type="text"
                                                                    x-bind:id="`job[{{ $loop->index }}][chassisTracker][${index}][engine_number]`"
                                                                    x-bind:name="`job[{{ $loop->index }}][chassisTracker][${index}][engine_number]`"
                                                                    x-model="chassisTracker.engine_number"
                                                                    placeholder="Engine Number"
                                                                />
                                                                <x-common.icon
                                                                    name="fas fa-th"
                                                                    class="is-small is-left"
                                                                />
                                                                <span
                                                                    class="help has-text-danger"
                                                                    x-text="$store.errors.getErrors(`job[{{ $loop->index }}][chassisTracker].${index}.engine_number`)"
                                                                ></span>
                                                            </x-forms.control>
                                                        </x-forms.field>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <x-common.button
                                        tag="button"
                                        type="button"
                                        mode="button"
                                        label="Add Chassis Number"
                                        class="bg-purple has-text-white is-small ml-3 mt-6"
                                        x-on:click="add($data.limit)"
                                    />
                                @endif
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

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("chassisTrackerMasterDetailForm", ({
                chassisTracker,
            }) => ({
                chassisTrackers: [],

                async init() {
                    if (chassisTracker) {
                        this.chassisTrackers = chassisTracker;

                        return;
                    }
                },
                add(limit) {
                    if (this.chassisTrackers.length < limit) {
                        this.chassisTrackers.push({});
                    }
                },
                remove(index) {
                    if (this.chassisTrackers.length <= 0) {
                        return;
                    }

                    this.chassisTrackers.splice(index, 1);

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

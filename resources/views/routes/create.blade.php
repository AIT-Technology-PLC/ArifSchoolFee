@extends('layouts.app')

@section('title', 'Create New Route')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        New Route
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('routes.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="routeMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('route.*')) }})"
            >
                <template
                    x-for="(route, index) in routes"
                    x-bind:key="index"
                >
                    <div class="mx-3">
                        <x-forms.field class="has-addons mb-0 mt-5">
                            <x-forms.control>
                                <span
                                    class="tag bg-softblue has-text-white is-medium is-radiusless"
                                    x-text="`Item ${index + 1}`"
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
                                        class="text-softblue"
                                    />
                                </x-common.button>
                            </x-forms.control>
                        </x-forms.field>
                        <div class="box has-background-white-bis radius-top-0">
                            <div class="columns is-marginless is-multiline">
                                <div class="column is-5">
                                    <x-forms.label x-bind:for="`route[${index}][title]`">
                                        Title <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`route[${index}][title]`"
                                                x-bind:name="`route[${index}][title]`"
                                                x-model="route.name"
                                                placeholder="Title"
                                            />
                                            <x-common.icon
                                                name="fas fa-heading"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`route.${index}.title`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-5">
                                    <x-forms.label x-bind:for="`route[${index}][fare]`">
                                        Fare <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="number"
                                                x-bind:id="`route[${index}][fare]`"
                                                x-bind:name="`route[${index}][fare]`"
                                                x-model="route.fare"
                                                placeholder="Fare"
                                            />
                                            <x-common.icon
                                                name="fas fa-money-bill"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`route.${index}.fare`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-2">
                                    <x-forms.label x-bind:for="`route[${index}][vehicle_id][]`">
                                        Vehicles <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field>
                                        @foreach ($vehicles as $vehicle)
                                            <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                                <input
                                                    type="checkbox"
                                                    x-bind:name="`route[${index}][vehicle_id][]`"
                                                    x-bind:value="{{ $vehicle->id }}"
                                                    {{ in_array($vehicle->id, old('`route.${index}.vehicle_id`', [])) ? 'checked' : '' }}
                                                >
                                                {{ $vehicle->vehicle_number }}
                                            </label>
                                            <br>
                                        @endforeach
                                    </x-forms.field>
                                    <span
                                        class="help has-text-danger"
                                        x-text="$store.errors.getErrors(`route.${index}.vehicle_id.*`)"
                                    ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <x-common.button
                    tag="button"
                    type="button"
                    mode="button"
                    label="Add More Item"
                    class="bg-purple has-text-white is-small ml-3 mt-6"
                    x-on:click="add"
                />
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("routeMasterDetailForm", ({
                route
            }) => ({
                routes: [],

                async init() {
                    if (route) {
                        this.routes = route;

                        return;
                    }

                    this.add();
                },
                add() {
                    this.routes.push({});
                },
                remove(index) {
                    if (this.routes.length <= 0) {
                        return;
                    }

                    this.routes.splice(index, 1);

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Edit Vehicle')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-blue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Vehicle
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('vehicles.update', $vehicle->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="vehicle_number">
                                Vehicle Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="vehicle_number"
                                    id="vehicle_number"
                                    placeholder="Vehicle Number"
                                    value="{{ $vehicle->vehicle_number }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="vehicle_number" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="vehicle_model">
                                Vehicle Model <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="vehicle_model"
                                    id="vehicle_model"
                                    placeholder="Vehicle Model"
                                    value="{{ $vehicle->vehicle_model }}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="vehicle_model" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="year_made">
                                Year Made <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    id="year_made"
                                    name="year_made"
                                    placeholder="Year Made"
                                    value="{{ $vehicle->year_made }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-days"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="year_made" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="driver_name">
                                Driver Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="driver_name"
                                    id="driver_name"
                                    placeholder="Driver Name"
                                    value="{{ $vehicle->driver_name }}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="driver_name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="driver_phone">
                                Driver Phone <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="driver_phone"
                                    name="driver_phone"
                                    type="number"
                                    placeholder="Driver Phone"
                                    value="{{ $vehicle->driver_phone }}"
                                />
                                <x-common.icon
                                    name="fas fa-phone"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="driver_phone" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="note">
                                Note <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.textarea
                                    name="note"
                                    id="note"
                                    rows="5"
                                    class="summernote textarea"
                                    placeholder="Description or note to be taken"
                                >{{  $vehicle->note ?? old('note') }}
                            </x-forms.textarea>
                            <x-common.validation-error property="note" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

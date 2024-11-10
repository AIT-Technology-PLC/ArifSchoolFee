@extends('layouts.app')

@section('title', 'Edit Route')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Route
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('routes.update', $route->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-5">
                        <x-forms.field>
                            <x-forms.label for="title">
                                Title <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="title"
                                    name="title"
                                    type="text"
                                    placeholder="Title"
                                    value="{{ $route->title }}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="title" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-5">
                        <x-forms.field>
                            <x-forms.label for="fare">
                                Fare <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="fare"
                                    name="fare"
                                    type="number"
                                    placeholder="Fare"
                                    value="{{ $route->fare }}"
                                />
                                <x-common.icon
                                    name="fas fa-money-bill"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="fare" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-2">
                        <x-forms.label for="vehicle_id[]">
                            Vehicles <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            @foreach ($vehicles as $vehicle)
                                <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                    <input
                                        type="checkbox"
                                        name="vehicle_id[]"
                                        value="{{ $vehicle->id }}"
                                        @checked($route->vehicles->contains($vehicle->id))
                                    >
                                    {{ $vehicle->vehicle_number }}
                                </label>
                                <br>
                            @endforeach
                        </x-forms.field>
                        <x-common.validation-error property="vehicle_id.*" />
                    </div>
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

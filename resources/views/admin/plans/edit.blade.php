@extends('layouts.app')

@section('title', 'Edit Plan')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Plan
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.plans.update', $plan->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="name">
                                Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="Plan Name"
                                    value="{{ old('name') ?? $plan->name }}"
                                />
                                <x-common.icon
                                    name="fas fa-tag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="feature_id[]">
                                Features <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    x-init="initializeSelect2($el, '')"
                                    class="is-fullwidth is-multiple"
                                    id="feature_id[]"
                                    name="feature_id[]"
                                    multiple
                                    style="width: 100% !important"
                                >
                                    @foreach ($features as $feature)
                                        <option
                                            value="{{ $feature->id }}"
                                            @selected(in_array($feature->name, old('features', $plan->features->pluck('name')->toArray())))
                                        >
                                            {{ $feature->name }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.validation-error property="feature_id.*" />
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

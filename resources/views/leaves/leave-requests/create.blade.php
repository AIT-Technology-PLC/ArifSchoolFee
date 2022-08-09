@extends('layouts.app')

@section('title', 'Create Leave')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Create New Leave" />
        <form
            id="formOne"
            action="{{ route('leave-requests.storeRequestLeave') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="leave_category_id">
                                Category <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="leave_category_id"
                                    name="leave_category_id"
                                >
                                    @foreach ($leaveCategories as $leaveCategory)
                                        <option value="{{ $leaveCategory->id }}">{{ $leaveCategory->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fa-solid fa-umbrella-beach"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="leave_category_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="starting_period">
                                Starting Period <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="starting_period"
                                    name="starting_period"
                                    type="datetime-local"
                                    placeholder="mm/dd/yyyy"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="starting_period" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="ending_period">
                                Ending Period <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="ending_period"
                                    name="ending_period"
                                    type="datetime-local"
                                    placeholder="mm/dd/yyyy"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="ending_period" />
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

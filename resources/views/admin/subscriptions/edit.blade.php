@extends('layouts.app')

@section('title', 'Edit Subscription')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Subscription" />
        <form
            id="formONe"
            action="{{ route('admin.subscriptions.update', $subscription->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="plan_id">
                                Plan <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="plan_id"
                                    name="plan_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Plan</option>
                                    @foreach ($plans as $plan)
                                        <option
                                            value="{{ $plan->id }}"
                                            @selected($plan->id == old('plan_id', $subscription->plan_id))
                                        >
                                            {{ str()->ucfirst($plan->name) }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-tag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="plan_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label for="starts_on">
                            Subscription Months <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="months"
                                    name="months"
                                    type="number"
                                    placeholder="Months (e.g. 12)"
                                    value="{{ old('months', $subscription->months) }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="months" />
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

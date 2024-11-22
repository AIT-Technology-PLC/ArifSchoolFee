@extends('layouts.app')

@section('title', $school->name)

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit School
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.schools.update', $school->id) }}"
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
                            <x-forms.label for="name">
                                School Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="School Name"
                                    value="{{ $school->name ?? old('name')}}"
                                />
                                <x-common.icon
                                    name="fas fa-bank"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="school_type_id">
                                School Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="school_type_id"
                                    name="school_type_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Type</option>
                                    @foreach ($schoolTypes as $schoolType)
                                        <option
                                            value="{{ $schoolType->id }}"
                                            @selected($schoolType->id == old('school_type_id', $school->schoolType->id))
                                        >
                                            {{ str()->ucfirst($schoolType->name) }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="school_type_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="plan_id">
                               Subscription Plan <sup class="has-text-danger">*</sup>
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
                                    >Select Subscription Plan</option>
                                    @foreach ($plans as $plan)
                                        <option
                                            value="{{ $plan->id }}"
                                            @selected($plan->id == old('plan_id', $school->plan->id))
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
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="enabled_commission_setting">
                                Status <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="enabled_commission_setting"
                                    name="enabled_commission_setting"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Status
                                    </option>
                                    <option
                                        value="1"
                                        @selected(old('enabled_commission_setting', $school->enabled_commission_setting) == '1')
                                    >
                                        Enable
                                    </option>
                                    <option
                                        value="0"
                                        @selected(old('enabled_commission_setting', $school->enabled_commission_setting) == '0')
                                    >
                                        Disable
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="enabled_commission_setting" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="charge_from">
                                Charge From <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="charge_from"
                                    name="charge_from"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select
                                    </option>
                                    <option
                                        value="payer"
                                        @selected(old('charge_from', $school->charge_from) == 'payer')
                                    >
                                        Payer
                                    </option>
                                    <option
                                        value="payee"
                                        @selected(old('charge_from', $school->charge_from) == 'payee')
                                    >
                                        Payee
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="charge_from" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.label>
                            Charge Type | Amount <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="charge_type"
                                    name="charge_type"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select
                                    </option>
                                    <option
                                        value="percent"
                                        @selected(old('charge_type', $school->charge_type) == 'percent')
                                    >
                                        Percent
                                    </option>
                                    <option
                                        value="amount"
                                        @selected(old('charge_type', $school->charge_type) == 'amount')
                                    >
                                        Amount
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="charge_type" />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.input
                                    id="charge_amount"
                                    type="number"
                                    name="charge_amount"
                                    placeholder="Amount"
                                    value="{{ $school->charge_amount }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="charge_amount" />
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

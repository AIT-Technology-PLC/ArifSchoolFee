@extends('layouts.app')

@section('title', 'Create New Company')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Company" />
        <form
            id="formOne"
            action="{{ route('admin.companies.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="company_name">
                                Company Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="company_name"
                                    name="company_name"
                                    type="text"
                                    placeholder="Company Name"
                                    value="{{ old('company_name') }}"
                                />
                                <x-common.icon
                                    name="fas fa-bank"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="company_name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
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
                                            @selected($plan->id == old('plan_id'))
                                        >
                                            {{ $plan->name }}
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
                    @foreach ($limits as $limit)
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="limit[{{ $limit->id }}][amount]">
                                    Number of {{ str($limit->name)->title()->plural() }} <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="limit[{{ $limit->id }}][amount]"
                                        name="limit[{{ $limit->id }}][amount]"
                                        type="number"
                                        placeholder="Number of {{ str($limit->name)->title()->plural() }}"
                                        value="{{ old('limit')[$limit->id]['amount'] ?? '' }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-cubes"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="limit.{{ $limit->id }}.amount" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endforeach
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="integrations[]">
                                Integrations <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    x-init="initializeSelect2($el, '')"
                                    class="is-fullwidth is-multiple"
                                    id="integrations[]"
                                    name="integrations[]"
                                    multiple
                                >
                                    @foreach ($integrations as $integration)
                                        <option
                                            value="{{ $integration->id }}"
                                            @selected(in_array($integration->id, old('integrations', [])))
                                        >
                                            {{ $integration->name }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.validation-error property="integrations.*" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="name">
                                Employee Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="Employee Name"
                                    value="{{ old('name') }}"
                                />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="email">
                                Employee Email <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="email"
                                    name="email"
                                    type="email"
                                    placeholder="Employee Email"
                                    value="{{ old('email') }}"
                                />
                                <x-common.icon
                                    name="fas fa-at"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="email" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Employee Password <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="New Password"
                                    autocomplete="new-password"
                                />
                                <x-common.icon
                                    name="fas fa-unlock"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="password" />
                            </x-forms.control>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="password-confirm"
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="Confirm Password"
                                    autocomplete="new-password"
                                />
                                <x-common.icon
                                    name="fas fa-unlock"
                                    class="is-small is-left"
                                />
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

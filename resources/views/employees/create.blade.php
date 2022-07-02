@extends('layouts.app')

@section('title', 'Add New Employee')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Employee" />
        <form
            id="formOne"
            action="{{ route('employees.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <x-common.fail-message :message="session('limitReachedMessage')" />
                <section>
                    <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3">
                        <h1 class="text-green is-size-5">
                            Basic Information
                        </h1>
                    </div>
                    <div class="box is-radiusless">
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="name">
                                        Name <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="name"
                                            name="name"
                                            type="text"
                                            placeholder="Employee Name"
                                            value="{{ old('name') }}"
                                            autocomplete="name"
                                            autofocus
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
                                        Email <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="email"
                                            name="email"
                                            type="text"
                                            placeholder="Email Address"
                                            value="{{ old('email') }}"
                                            autocomplete="email"
                                        />
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-at"></i>
                                        </span>
                                        <x-common.icon
                                            name="fas fa-at"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="email" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="password">
                                        Password <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="password"
                                            name="password"
                                            type="password"
                                            placeholder="Employee Password"
                                            autocomplete="new-password"
                                        />
                                        <x-common.icon
                                            name="fas fa-unlock"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="password" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="password-confirm">
                                        Confrim Password <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
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
                                        <x-common.validation-error property="password" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="position">
                                        Job Title/Position <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="position"
                                            name="position"
                                            type="text"
                                            placeholder="Job Title"
                                            value="{{ old('position') ?? '' }}"
                                        />
                                        <x-common.icon
                                            name="fas fa-user-tie"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="position" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="warehouse_id">
                                        Assign To <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="warehouse_id"
                                            name="warehouse_id"
                                        >
                                            @foreach ($warehouses as $warehouse)
                                                <option
                                                    value="{{ $warehouse->id }}"
                                                    {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}
                                                >{{ $warehouse->name }}</option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-warehouse"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="warehouse_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="enabled">
                                        Can this employee access the system? <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control>
                                        <label class="radio has-text-grey has-text-weight-light">
                                            <input
                                                type="radio"
                                                name="enabled"
                                                value="1"
                                                class="mt-3"
                                                {{ old('enabled') == 1 ? 'checked' : '' }}
                                            >
                                            Yes, this employee can access the system
                                        </label>
                                        <br>
                                        <label class="radio has-text-grey has-text-weight-light mt-2">
                                            <input
                                                type="radio"
                                                name="enabled"
                                                value="0"
                                                {{ old('enabled') == 0 ? 'checked' : '' }}
                                            >
                                            No, this employee can't access the system
                                        </label>
                                        <x-common.validation-error property="enabled" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="role">
                                        Choose Role <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control>
                                        @foreach ($roles as $role)
                                            <label class="radio has-text-grey has-text-weight-light">
                                                <input
                                                    type="radio"
                                                    name="role"
                                                    value="{{ $role->name }}"
                                                    class="mt-3"
                                                    {{ old('role') == $role->name ? 'checked' : '' }}
                                                >
                                                {{ $role->name }}
                                            </label>
                                            <br>
                                        @endforeach
                                        <x-common.validation-error property="role" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="mt-5">
                    <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3">
                        <h1 class="text-green is-size-5">
                            Branch Permissions
                        </h1>
                    </div>
                    <div class="box is-radiusless">
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-3">
                                <label
                                    for="transactions[]"
                                    class="label text-green"
                                > Transactions <sup class="has-text-danger"></sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="transactions[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('transactions', [])) ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                </x-forms.field>
                                <x-common.validation-error property="transactions.*" />
                            </div>
                            <div class="column is-3">
                                <label
                                    for="read[]"
                                    class="label text-green"
                                > Inventory Level <sup class="has-text-danger"></sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="read[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('read', [])) ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                </x-forms.field>
                                <x-common.validation-error property="read.*" />
                            </div>
                            <div class="column is-3">
                                <label
                                    for="sales[]"
                                    class="label text-green"
                                > Sales <sup class="has-text-weight-light is-size-7">(Delivery Order, Reservation)</sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="sales[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('sales', [])) == $warehouse->id ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    <x-common.validation-error property="sales.*" />
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <label
                                    for="subtract[]"
                                    class="label text-green"
                                > Subtract <sup class="has-text-weight-light is-size-7">(Transfer, Damage)</sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="subtract[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('subtract', [])) ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    <x-common.validation-error property="subtract.*" />
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <label
                                    for="add[]"
                                    class="label text-green"
                                > Add <sup class="has-text-weight-light is-size-7">(Transfer, Return, GRN)</sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="add[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('add', [])) == $warehouse->id ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    <x-common.validation-error property="add.*" />
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <label
                                    for="adjustment[]"
                                    class="label text-green"
                                > Adjustment <sup class="has-text-danger"></sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="adjustment[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('adjustment', [])) == $warehouse->id ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    <x-common.validation-error property="adjustment.*" />
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <label
                                    for="siv[]"
                                    class="label text-green"
                                > SIV <sup class="has-text-danger"></sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="siv[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('siv', [])) == $warehouse->id ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    <x-common.validation-error property="siv.*" />
                                </x-forms.field>
                            </div>
                        </div>
                    </div>
                </section>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

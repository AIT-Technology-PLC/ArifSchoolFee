@extends('layouts.app')

@section('title')
    Add New Employee
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Employee
            </h1>
        </div>
        <form id="formOne" action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <section>
                    <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3">
                        <h1 class="text-green is-size-5">
                            Basic Information
                        </h1>
                    </div>
                    <div class="box is-radiusless">
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label for="name" class="label text-green has-text-weight-normal">Name <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <input id="name" name="name" type="text" class="input" placeholder="Employee Name" value="{{ old('name') }}" autocomplete="name" autofocus>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        @error('name')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="email" class="label text-green has-text-weight-normal">Email <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <input id="email" name="email" type="text" class="input" placeholder="Email Address" value="{{ old('email') }}" autocomplete="email">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-at"></i>
                                        </span>
                                        @error('email')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="password" class="label text-green has-text-weight-normal">Password <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <input id="password" name="password" type="password" class="input" placeholder="Employee Password" autocomplete="new-password">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-unlock"></i>
                                        </span>
                                        @error('password')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="password-confirm" class="label text-green has-text-weight-normal">Confrim Password <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <input id="password-confirm" type="password" class="input" name="password_confirmation" placeholder="Confirm Password" autocomplete="new-password">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-unlock"></i>
                                        </span>
                                        @error('password')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="position" class="label text-green has-text-weight-normal">Job Title/Position <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <input id="position" name="position" type="text" class="input" placeholder="Job Title" value="{{ old('position') ?? '' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-user-tie"></i>
                                        </span>
                                        @error('position')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="warehouse_id" class="label text-green has-text-weight-normal"> Assign To <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <div class="select is-fullwidth">
                                            <select id="warehouse_id" name="warehouse_id">
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-warehouse"></i>
                                        </div>
                                        @error('warehouse_id')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="enabled" class="label text-green has-text-weight-normal"> Can this employee access the system? <sup class="has-text-danger">*</sup> </label>
                                    <div class="control">
                                        <label class="radio has-text-grey has-text-weight-light">
                                            <input type="radio" name="enabled" value="1" class="mt-3" {{ old('enabled') == 1 ? 'checked' : '' }}>
                                            Yes, this employee can access the system
                                        </label>
                                        <br>
                                        <label class="radio has-text-grey has-text-weight-light mt-2">
                                            <input type="radio" name="enabled" value="0" {{ old('enabled') == 0 ? 'checked' : '' }}>
                                            No, this employee can't access the system
                                        </label>
                                        @error('enabled')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="role" class="label text-green has-text-weight-normal"> Choose Role <sup class="has-text-danger">*</sup> </label>
                                    <div class="control">
                                        @foreach ($roles as $role)
                                            <label class="radio has-text-grey has-text-weight-light">
                                                <input type="radio" name="role" value="{{ $role->name }}" class="mt-3" {{ old('role') == $role->name ? 'checked' : '' }}>
                                                {{ $role->name }}
                                            </label>
                                            <br>
                                        @endforeach
                                        @error('role')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
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
                                <label for="read[]" class="label text-green"> Inventory Level <sup class="has-text-danger"></sup> </label>
                                <div class="field">
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input name="read[]" value="{{ $warehouse->id }}" type="checkbox" {{ in_array($warehouse->id, old('read', [])) ? 'checked' : '' }}>
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                </div>
                                @error('read.*')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="column is-3">
                                <label for="sales[]" class="label text-green"> Sales <sup class="has-text-weight-light is-size-7">(Delivery Order, Reservation)</sup> </label>
                                <div class="field">
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input name="sales[]" value="{{ $warehouse->id }}" type="checkbox" {{ in_array($warehouse->id, old('sales', [])) == $warehouse->id ? 'checked' : '' }}>
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    @error('sales.*')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="column is-3">
                                <label for="subtract[]" class="label text-green"> Subtract <sup class="has-text-weight-light is-size-7">(Transfer, Damage)</sup> </label>
                                <div class="field">
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input name="subtract[]" value="{{ $warehouse->id }}" type="checkbox" {{ in_array($warehouse->id, old('subtract', [])) ? 'checked' : '' }}>
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    @error('subtract.*')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="column is-3">
                                <label for="add[]" class="label text-green"> Add <sup class="has-text-weight-light is-size-7">(Transfer, Return, GRN)</sup> </label>
                                <div class="field">
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input name="add[]" value="{{ $warehouse->id }}" type="checkbox" {{ in_array($warehouse->id, old('add', [])) == $warehouse->id ? 'checked' : '' }}>
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    @error('add.*')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="column is-3">
                                <label for="adjustment[]" class="label text-green"> Adjustment <sup class="has-text-danger"></sup> </label>
                                <div class="field">
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input name="adjustment[]" value="{{ $warehouse->id }}" type="checkbox" {{ in_array($warehouse->id, old('adjustment', [])) == $warehouse->id ? 'checked' : '' }}>
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    @error('adjustment.*')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="column is-3">
                                <label for="siv[]" class="label text-green"> SIV <sup class="has-text-danger"></sup> </label>
                                <div class="field">
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input name="siv[]" value="{{ $warehouse->id }}" type="checkbox" {{ in_array($warehouse->id, old('siv', [])) == $warehouse->id ? 'checked' : '' }}>
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    @error('siv.*')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="box radius-top-0">
                <x-common.save-button />
            </div>
        </form>
    </section>
@endsection

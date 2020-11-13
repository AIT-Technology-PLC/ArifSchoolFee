@extends('layouts.app')

@section('title')
Set Employee Access Permissions
@endsection

@section('content')
<section class="mt-3 mx-3 m-lr-0">
    <div class="box radius-bottom-0 mb-0 has-background-white-bis">
        <h1 class="title text-green has-text-weight-medium is-size-5">
            Set Employee Access Permissions - {{ $permission->employee->user->name }}
        </h1>
    </div>
    <form action="{{ route('permissions.update', $permission->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method("PATCH")
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <div class="columns is-marginless is-vcentered">
                <div class="column is-6 has-text-left">
                    <span class="text-green">
                        Available Modules
                    </span>
                </div>
                <div class="column is-6 has-text-left">
                    <span class="text-green">
                        Permission Levels
                    </span>
                </div>
            </div>
            <hr class="my-0 mb-3">
            <div class="columns is-marginless is-vcentered">
                <div class="column is-6 has-text-left">
                    <div class="text-purple">
                        Settings and User Management
                    </div>
                    <div class="has-text-grey is-size-7">
                        Managing employee accounts, company profile, and other settings.
                    </div>
                </div>
                <div class="column is-6">
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium">
                        <input type="checkbox" name="settings[]" value="c" {{ Str::contains($permission->settings, 'c') ? 'checked' : '' }}>
                        Create
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="settings[]" value="r" {{ Str::contains($permission->settings, 'r') ? 'checked' : '' }}>
                        Read
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="settings[]" value="u" {{ Str::contains($permission->settings, 'u') ? 'checked' : '' }}>
                        Update
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="settings[]" value="d" {{ Str::contains($permission->settings, 'd') ? 'checked' : '' }}>
                        Delete
                    </label>
                </div>
            </div>
            <div class="columns is-marginless is-vcentered">
                <div class="column is-6 has-text-left">
                    <div class="text-purple">
                        Warehouse Management
                    </div>
                    <div class="has-text-grey is-size-7">
                        Control multiple warehouses operations.
                    </div>
                </div>
                <div class="column is-6">
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium">
                        <input type="checkbox" name="warehouses[]" value="c" {{ Str::contains($permission->warehouses, 'c') ? 'checked' : '' }}>
                        Create
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="warehouses[]" value="r" {{ Str::contains($permission->warehouses, 'r') ? 'checked' : '' }}>
                        Read
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="warehouses[]" value="u" {{ Str::contains($permission->warehouses, 'u') ? 'checked' : '' }}>
                        Update
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="warehouses[]" value="d" {{ Str::contains($permission->warehouses, 'd') ? 'checked' : '' }}>
                        Delete
                    </label>
                </div>
            </div>
            <div class="columns is-marginless is-vcentered">
                <div class="column is-6 has-text-left">
                    <div class="text-purple">
                        Products and Categories Management
                    </div>
                    <div class="has-text-grey is-size-7">
                        Manage and oversee products and categories offered.
                    </div>
                </div>
                <div class="column is-6">
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium">
                        <input type="checkbox" name="products[]" value="c" {{ Str::contains($permission->products, 'c') ? 'checked' : '' }}>
                        Create
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="products[]" value="r" {{ Str::contains($permission->products, 'r') ? 'checked' : '' }}>
                        Read
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="products[]" value="u" {{ Str::contains($permission->products, 'u') ? 'checked' : '' }}>
                        Update
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="products[]" value="d" {{ Str::contains($permission->products, 'd') ? 'checked' : '' }}>
                        Delete
                    </label>
                </div>
            </div>
            <div class="columns is-marginless is-vcentered">
                <div class="column is-6 has-text-left">
                    <div class="text-purple">
                        Merchandise Inventory Management
                    </div>
                    <div class="has-text-grey is-size-7">
                        Receive new products, and manage & track product status.
                    </div>
                </div>
                <div class="column is-6">
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium">
                        <input type="checkbox" name="merchandises[]" value="c" {{ Str::contains($permission->merchandises, 'c') ? 'checked' : '' }}>
                        Create
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="merchandises[]" value="r" {{ Str::contains($permission->merchandises, 'r') ? 'checked' : '' }}>
                        Read
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="merchandises[]" value="u" {{ Str::contains($permission->merchandises, 'u') ? 'checked' : '' }}>
                        Update
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="merchandises[]" value="d" {{ Str::contains($permission->merchandises, 'd') ? 'checked' : '' }}>
                        Delete
                    </label>
                </div>
            </div>
            <div class="columns is-marginless is-vcentered">
                <div class="column is-6 has-text-left">
                    <div class="text-purple">
                        Manufacturing Inventory Management
                    </div>
                    <div class="has-text-grey is-size-7">
                        Start new production tasks, track materials, MRO and more.
                    </div>
                </div>
                <div class="column is-6">
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium">
                        <input type="checkbox" name="manufacturings[]" value="c" {{ Str::contains($permission->manufacturings, 'c') ? 'checked' : '' }}>
                        Create
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="manufacturings[]" value="r" {{ Str::contains($permission->manufacturings, 'r') ? 'checked' : '' }}>
                        Read
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="manufacturings[]" value="u" {{ Str::contains($permission->manufacturings, 'u') ? 'checked' : '' }}>
                        Update
                    </label>
                    <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-6 m-lr-0">
                        <input type="checkbox" name="manufacturings[]" value="d" {{ Str::contains($permission->manufacturings, 'd') ? 'checked' : '' }}>
                        Delete
                    </label>
                </div>
            </div>
        </div>
        <div class="box radius-top-0">
            <div class="columns is-marginless">
                <div class="column is-paddingless">
                    <div class="buttons is-right">
                        <button class="button is-white text-green" type="reset">
                            <span class="icon">
                                <i class="fas fa-times"></i>
                            </span>
                            <span>
                                Cancel
                            </span>
                        </button>
                        <button class="button bg-green has-text-white">
                            <span class="icon">
                                <i class="fas fa-save"></i>
                            </span>
                            <span>
                                Save
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

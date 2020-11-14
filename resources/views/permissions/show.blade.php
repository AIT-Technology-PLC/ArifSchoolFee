@extends('layouts.app')

@section('title')
Your Access Permissions
@endsection

@section('content')
<section class="mt-3 mx-3 m-lr-0">
    <div class="box radius-bottom-0 mb-0 has-background-white-bis">
        <h1 class="title text-green has-text-weight-medium is-size-5">
            Your Access Permissions
        </h1>
    </div>
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
                    <input type="checkbox" name="settings[]" {{ Str::contains($permission->settings, 'c') ? 'checked' : '' }} disabled>
                    Create
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="settings[]" {{ Str::contains($permission->settings, 'r') ? 'checked' : '' }} disabled>
                    Read
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="settings[]" {{ Str::contains($permission->settings, 'u') ? 'checked' : '' }} disabled>
                    Update
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="settings[]" {{ Str::contains($permission->settings, 'd') ? 'checked' : '' }} disabled>
                    Delete
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="settings[]" {{ Str::length($permission->settings) == 0 ? 'checked' : '' }} disabled>
                    None
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
                    <input type="checkbox" name="warehouses[]" {{ Str::contains($permission->warehouses, 'c') ? 'checked' : '' }} disabled>
                    Create
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="warehouses[]" {{ Str::contains($permission->warehouses, 'r') ? 'checked' : '' }} disabled>
                    Read
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="warehouses[]" {{ Str::contains($permission->warehouses, 'u') ? 'checked' : '' }} disabled>
                    Update
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="warehouses[]" {{ Str::contains($permission->warehouses, 'd') ? 'checked' : '' }} disabled>
                    Delete
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="warehouses[]" {{ Str::length($permission->warehouses) == 0 ? 'checked' : '' }} disabled>
                    None
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
                    <input type="checkbox" name="products[]" {{ Str::contains($permission->products, 'c') ? 'checked' : '' }} disabled>
                    Create
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="products[]" {{ Str::contains($permission->products, 'r') ? 'checked' : '' }} disabled>
                    Read
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="products[]" {{ Str::contains($permission->products, 'u') ? 'checked' : '' }} disabled>
                    Update
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="products[]" {{ Str::contains($permission->products, 'd') ? 'checked' : '' }} disabled>
                    Delete
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="products[]" {{ Str::length($permission->products) == 0 ? 'checked' : '' }} disabled>
                    None
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
                    <input type="checkbox" name="merchandises[]" {{ Str::contains($permission->merchandises, 'c') ? 'checked' : '' }} disabled>
                    Create
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="merchandises[]" {{ Str::contains($permission->merchandises, 'r') ? 'checked' : '' }} disabled>
                    Read
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="merchandises[]" {{ Str::contains($permission->merchandises, 'u') ? 'checked' : '' }} disabled>
                    Update
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="merchandises[]" {{ Str::contains($permission->merchandises, 'd') ? 'checked' : '' }} disabled>
                    Delete
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="merchandises[]" {{ Str::length($permission->merchandises) == 0 ? 'checked' : '' }} disabled>
                    None
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
                    <input type="checkbox" name="manufacturings[]" {{ Str::contains($permission->manufacturings, 'c') ? 'checked' : '' }} disabled>
                    Create
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="manufacturings[]" {{ Str::contains($permission->manufacturings, 'r') ? 'checked' : '' }} disabled>
                    Read
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="manufacturings[]" {{ Str::contains($permission->manufacturings, 'u') ? 'checked' : '' }} disabled>
                    Update
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="manufacturings[]" {{ Str::contains($permission->manufacturings, 'd') ? 'checked' : '' }} disabled>
                    Delete
                </label>
                <label class="checkbox is-uppercase text-purple has-text-weight-medium ml-5 m-lr-0">
                    <input type="checkbox" name="manufacturings[]" {{ Str::length($permission->manufacturings) == 0 ? 'checked' : '' }} disabled>
                    None
                </label>
            </div>
        </div>
    </div>
    <div class="box radius-top-0">
        <div class="columns is-marginless">
            <div class="column is-paddingless">
                <p class="text-green is-uppercase is-size-7 my-2">
                    Please refer to your System Administrator to know more about your access permissions.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

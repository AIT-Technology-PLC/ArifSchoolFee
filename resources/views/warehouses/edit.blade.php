@extends('layouts.app')

@section('title')
    Edit Warehouse - {{ $warehouse->name }}
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit Warehouse - {{ $warehouse->name }}
            </h1>
        </div>
        <form
            id="formOne"
            action="{{ route('warehouses.update', $warehouse->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <x-common.fail-message :message="session('limitReachedMessage')" />
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="name"
                                class="label text-green has-text-weight-normal"
                            >Warehouse Name <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="input"
                                    placeholder="Warehouse Name"
                                    value="{{ $warehouse->name ?? '' }}"
                                >
                                <span class="icon is-small is-left">
                                    <i class="fas fa-warehouse"></i>
                                </span>
                                @error('name')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="location"
                                class="label text-green has-text-weight-normal"
                            >Location <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    id="location"
                                    name="location"
                                    type="text"
                                    class="input"
                                    placeholder="Location: Building, Street"
                                    value="{{ $warehouse->location ?? '' }}"
                                >
                                <span class="icon is-small is-left">
                                    <i class="fas fa-location-arrow"></i>
                                </span>
                                @error('location')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="pos_provider"
                                class="label text-green has-text-weight-normal"
                            > POS Provider <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select
                                        id="pos_provider"
                                        name="pos_provider"
                                    >
                                        <option disabled>
                                            Select Provider
                                        </option>
                                        <option
                                            value="Peds"
                                            @selected($warehouse->pos_provider == 'Peds')
                                        >
                                            Peds
                                        </option>
                                        <option
                                            value=""
                                            @selected($warehouse->pos_provider == '')
                                        >
                                            None
                                        </option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-cash-register"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="is_active"
                                class="label text-green has-text-weight-normal"
                            > Active or not <sup class="has-text-danger">*</sup> </label>
                            <div class="control">
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        type="radio"
                                        name="is_active"
                                        value="1"
                                        class="mt-3"
                                        {{ $warehouse->isActive() ? 'checked' : '' }}
                                    >
                                    Active
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        type="radio"
                                        name="is_active"
                                        value="0"
                                        {{ $warehouse->isActive() ? '' : 'checked' }}
                                    >
                                    Not Active
                                </label>
                                @error('is_active')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="is_sales_store"
                                class="label text-green has-text-weight-normal"
                            > Store Type <sup class="has-text-danger">*</sup> </label>
                            <div class="control">
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        type="radio"
                                        name="is_sales_store"
                                        value="1"
                                        class="mt-3"
                                        {{ $warehouse->is_sales_store ? 'checked' : '' }}
                                    >
                                    Sales Store
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        type="radio"
                                        name="is_sales_store"
                                        value="0"
                                        {{ $warehouse->is_sales_store ? '' : 'checked' }}
                                    >
                                    Main Store
                                </label>
                                @error('is_sales_store')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="can_be_sold_from"
                                class="label text-green has-text-weight-normal"
                            > Can be sold from? <sup class="has-text-danger">*</sup> </label>
                            <div class="control">
                                <label class="radio has-text-grey has-text-weight-normal">
                                    <input
                                        type="radio"
                                        name="can_be_sold_from"
                                        value="1"
                                        class="mt-3"
                                        {{ $warehouse->can_be_sold_from ? 'checked' : '' }}
                                    >
                                    Yes
                                </label>
                                <label class="radio has-text-grey has-text-weight-normal mt-2">
                                    <input
                                        type="radio"
                                        name="can_be_sold_from"
                                        value="0"
                                        {{ $warehouse->can_be_sold_from ? '' : 'checked' }}
                                    >
                                    No
                                </label>
                                @error('can_be_sold_from')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="email"
                                class="label text-green has-text-weight-normal"
                            >Email <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    id="email"
                                    name="email"
                                    type="text"
                                    class="input"
                                    placeholder="Email Address"
                                    value="{{ $warehouse->email ?? '' }}"
                                >
                                <span class="icon is-small is-left">
                                    <i class="fas fa-at"></i>
                                </span>
                                @error('email')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label
                                for="phone"
                                class="label text-green has-text-weight-normal"
                            >Phone <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    class="input"
                                    placeholder="Phone/Telephone"
                                    value="{{ $warehouse->phone ?? '' }}"
                                >
                                <span class="icon is-small is-left">
                                    <i class="fas fa-phone"></i>
                                </span>
                                @error('phone')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="field">
                            <label
                                for="description"
                                class="label text-green has-text-weight-normal"
                            >Description</label>
                            <div class="control has-icons-left">
                                <textarea
                                    name="description"
                                    id="description"
                                    cols="30"
                                    rows="10"
                                    class="textarea pl-6"
                                    placeholder="Description or note about the new warehouse"
>{{ $warehouse->description ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-edit"></i>
                                </span>
                                @error('description')
                                    <span
                                        class="help has-text-danger"
                                        role="alert"
                                    >
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box radius-top-0">
                <x-common.save-button />
            </div>
        </form>
    </section>
@endsection

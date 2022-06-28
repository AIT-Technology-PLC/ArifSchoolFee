@extends('layouts.app')

@section('title')
    Create New Damage
@endsection

@section('content')
    <x-common.content-wrapper>
        <x-content.header title=" New Damage" />
        <form
            id="formOne"
            action="{{ route('damages.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Damage Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $currentDamageCode }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="issued_on">
                                Issued On <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="datetime-local"
                                    name="issued_on"
                                    id="issued_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('issued_on') ?? now()->toDateTimeLocalString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="issued_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="textarea pl-6"
                                    placeholder="Description or note to be taken"
                                >{{ old('description') ?? '' }}</x-forms.textarea>
                                <x-common.icon
                                    name="fas fa-edit"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
                <div id="damage-details">
                    @foreach (old('damage', [[]]) as $damageDetail)
                        <div
                            x-data="productDataProvider({{ $damageDetail['product_id'] ?? '' }})"
                            class="damage-detail mx-3"
                        >
                            <x-forms.field class="has-addons mb-0 mt-5">
                                <x-forms.control>
                                    <span
                                        name="item-number"
                                        class="tag bg-green has-text-white is-medium is-radiusless"
                                    >
                                        Item {{ $loop->iteration }}
                                    </span>
                                </x-forms.control>
                                <x-forms.control>
                                    <x-common.button
                                        tag="button"
                                        name="remove-detail-button"
                                        type="button"
                                        class="bg-lightgreen has-text-white is-medium is-radiusless is-pointer"
                                    >
                                        <x-common.icon
                                            name="fas fa-times-circle"
                                            class="text-green"
                                        />
                                    </x-common.button>
                                </x-forms.control>
                            </x-forms.field>
                            <div class="box has-background-white-bis radius-top-0">
                                <div
                                    name="damageFormGroup"
                                    class="columns is-marginless is-multiline"
                                >
                                    <div class="column is-6">
                                        <x-forms.label for="damage[{{ $loop->index }}][product_id]">
                                            Product <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field class="has-addons">
                                            <x-forms.control
                                                class="has-icons-left"
                                                style="width: 30%"
                                            >
                                                <x-common.category-list
                                                    x-model="selectedCategory"
                                                    x-on:change="getProductsByCategory"
                                                />
                                            </x-forms.control>
                                            <x-forms.control class="has-icons-left is-expanded">
                                                <x-common.product-list
                                                    tags="false"
                                                    name="damage[{{ $loop->index }}]"
                                                    selected-product-id="{{ $damageDetail['product_id'] ?? '' }}"
                                                    x-init="select2"
                                                />
                                                <x-common.icon
                                                    name="fas fa-th"
                                                    class="is-small is-left"
                                                />
                                                @error('damage.' . $loop->index . '.product_id')
                                                    <span
                                                        class="help has-text-danger"
                                                        role="alert"
                                                    >
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6">
                                        <x-forms.field>
                                            <x-forms.label for="damage[{{ $loop->index }}][warehouse_id]">
                                                From <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.select
                                                    class="is-fullwidth"
                                                    id="damage[{{ $loop->index }}][warehouse_id]"
                                                    name="damage[{{ $loop->index }}][warehouse_id]"
                                                >
                                                    @foreach ($warehouses as $warehouse)
                                                        <option
                                                            value="{{ $warehouse->id }}"
                                                            {{ ($damageDetail['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                                        >{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </x-forms.select>
                                                <x-common.icon
                                                    name="fas fa-warehouse"
                                                    class="is-small is-left"
                                                />
                                                @error('damage.' . $loop->index . '.warehouse_id')
                                                    <span
                                                        class="help has-text-danger"
                                                        role="alert"
                                                    >
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6">
                                        <x-forms.label for="damage[{{ $loop->index }}][quantity]">
                                            Quantity <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field class="has-addons">
                                            <x-forms.control class="has-icons-left is-expanded">
                                                <x-forms.input
                                                    id="damage[{{ $loop->index }}][quantity]"
                                                    name="damage[{{ $loop->index }}][quantity]"
                                                    type="number"
                                                    placeholder="Quantity"
                                                    value="{{ $damageDetail['quantity'] ?? '' }}"
                                                />
                                                <x-common.icon
                                                    name="fas fa-balance-scale"
                                                    class="is-small is-left"
                                                />
                                                @error('damage.' . $loop->index . '.quantity')
                                                    <span
                                                        class="help has-text-danger"
                                                        role="alert"
                                                    >
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </x-forms.control>
                                            <x-forms.control>
                                                <x-common.button
                                                    tag="button"
                                                    id="damage[{{ $loop->index }}][product_id]Quantity"
                                                    class="button bg-green has-text-white"
                                                    type="button"
                                                    x-text="product.unit_of_measurement"
                                                ></x-common.button>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6">
                                        <x-forms.field>
                                            <x-forms.label for="damage[{{ $loop->index }}][description]">
                                                Additional Notes <sup class="has-text-danger"></sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.textarea
                                                    name="damage[{{ $loop->index }}][description]"
                                                    id="damage[{{ $loop->index }}][description]"
                                                    class="textarea pl-6"
                                                    placeholder="Description or note to be taken"
                                                >{{ $damageDetail['description'] ?? '' }}</x-forms.textarea>
                                                <x-common.icon
                                                    name="fas fa-edit"
                                                    class="is-large is-left"
                                                />
                                                @error('damage.' . $loop->index . '.description')
                                                    <span
                                                        class="help has-text-danger"
                                                        role="alert"
                                                    >
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <x-common.button
                    id="addNewDamageForm"
                    tag="button"
                    type="button"
                    mode="button"
                    label="Add More Item"
                    class="bg-purple has-text-white is-small ml-3 mt-6"
                />
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

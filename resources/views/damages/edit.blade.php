@extends('layouts.app')

@section('title')
    Edit Damage
@endsection

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Damage" />
        <form
            id="formOne"
            action="{{ route('damages.update', $damage->id) }}"
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
                            <x-forms.label for="code">
                                Damage Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $damage->code }}"
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
                                    value="{{ $damage->issued_on->toDateTimeLocalString() }}"
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
                                >{{ $damage->description ?? '' }}</x-forms.textarea>
                                <x-common.icon
                                    name="fas fa-edit"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
                @foreach ($damage->damageDetails as $damageDetail)
                    <div class="has-text-weight-medium has-text-left mt-5">
                        <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                            Item {{ $loop->index + 1 }}
                        </span>
                    </div>
                    <div
                        x-data="productDataProvider({{ $damageDetail->product_id }})"
                        class="box has-background-white-bis radius-top-0"
                    >
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
                                            selected-product-id="{{ $damageDetail->product_id }}"
                                            x-init="select2"
                                        />
                                        <x-common.icon
                                            name="fas fa-th"
                                            class="is-small is-left"
                                        />
                                        @error('damage.0.product_id')
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
                                                    {{ $damageDetail->warehouse_id == $warehouse->id ? 'selected' : '' }}
                                                >{{ $warehouse->name }}</option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-warehouse"
                                            class="is-small is-left"
                                        />
                                        @error('damage.0.warehouse_id')
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
                                            value="{{ $damageDetail->quantity }}"
                                        />
                                        <x-common.icon
                                            name="fas fa-balance-scale"
                                            class="is-small is-left"
                                        />
                                        @error('damage.0.quantity')
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
                                        >{{ $damageDetail->description ?? '' }}</x-forms.textarea>
                                        <x-common.icon
                                            name="fas fa-edit"
                                            class="is-large is-left"
                                        />
                                        @error('damage.0.description')
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
                @endforeach
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

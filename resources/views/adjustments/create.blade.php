@extends('layouts.app')

@section('title', 'Create Adjustment')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Adjustment" />
        <form
            id="formOne"
            action="{{ route('adjustments.store') }}"
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
                                Adjustment Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $currentAdjustmentCode }}"
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
                                    type="date"
                                    name="issued_on"
                                    id="issued_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ old('issued_on') ?? now()->toDateString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-alt"
                                    class="is-large is-left"
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
                                    class="pl-6"
                                    placeholder="Description or note to be taken"
                                >
                                    {{ old('description') ?? '' }}
                                </x-forms.textarea>
                                <x-common.icon
                                    name="fas fa-edit"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
                <div id="adjustment-details">
                    @foreach (old('adjustment', [0]) as $adjustmentDetail)
                        <div class="adjustment-detail mx-3">
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
                                        mode="tag"
                                        type="button"
                                        name="remove-detail-button"
                                        class="bg-lightgreen has-text-white is-medium is-radiusless"
                                    >
                                        <x-common.icon
                                            name="fas fa-times-circle"
                                            class="text-green"
                                        />
                                    </x-common.button>
                                </x-forms.control>
                            </x-forms.field>
                            <div class="box has-background-white-bis radius-top-0">
                                <div class="columns is-marginless is-multiline">
                                    <div class="column is-6">
                                        <x-forms.field>
                                            <x-forms.label for="adjustment[{{ $loop->index }}][product_id]">
                                                Product <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-common.product-list
                                                    tags="false"
                                                    name="adjustment[{{ $loop->index }}]"
                                                    selected-product-id="{{ $adjustmentDetail['product_id'] ?? '' }}"
                                                />
                                                <x-common.icon
                                                    name="fas fa-th"
                                                    class="is-large is-left"
                                                />
                                                <x-common.validation-error property="adjustment.{{ $loop->index }}.product_id" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6">
                                        <x-forms.label for="adjustment[{{ $loop->index }}][quantity]">
                                            Quantity <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field class="has-addons">
                                            <x-forms.control class="has-icons-left is-expanded">
                                                <x-forms.input
                                                    id="adjustment[{{ $loop->index }}][quantity]"
                                                    name="adjustment[{{ $loop->index }}][quantity]"
                                                    type="number"
                                                    placeholder="Quantity"
                                                    value="{{ $adjustmentDetail['quantity'] ?? '' }}"
                                                />
                                                <x-common.icon
                                                    name="fas fa-balance-scale"
                                                    class="is-small is-left"
                                                />
                                                <x-common.validation-error property="adjustment.{{ $loop->index }}.quantity" />
                                            </x-forms.control>
                                            <x-forms.control>
                                                <x-common.button
                                                    tag="button"
                                                    type="button"
                                                    mode="button"
                                                    id="adjustment[{{ $loop->index }}][product_id]Quantity"
                                                    class="bg-green has-text-white"
                                                />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6">
                                        <x-forms.field>
                                            <x-forms.label for="adjustment[{{ $loop->index }}][is_subtract]">
                                                Operation <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.select
                                                    class="is-fullwidth"
                                                    id="adjustment[{{ $loop->index }}][is_subtract]"
                                                    name="adjustment[{{ $loop->index }}][is_subtract]"
                                                >
                                                    <option
                                                        value="0"
                                                        {{ ($adjustmentDetail['is_subtract'] ?? '') == 0 ? 'selected' : '' }}
                                                    > Add </option>
                                                    <option
                                                        value="1"
                                                        {{ ($adjustmentDetail['is_subtract'] ?? '') == 1 ? 'selected' : '' }}
                                                    > Subtract </option>
                                                </x-forms.select>
                                                <x-common.icon
                                                    name="fas fa-sort"
                                                    class="is-small is-left"
                                                />
                                                <x-common.validation-error property="adjustment.{{ $loop->index }}.is_subtract" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6">
                                        <x-forms.field>
                                            <x-forms.label for="adjustment[{{ $loop->index }}][warehouse_id]">
                                                Warehouse <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.select
                                                    class="is-fullwidth"
                                                    id="adjustment[{{ $loop->index }}][warehouse_id]"
                                                    name="adjustment[{{ $loop->index }}][warehouse_id]"
                                                >
                                                    @foreach ($warehouses as $warehouse)
                                                        <option
                                                            value="{{ $warehouse->id }}"
                                                            {{ ($adjustmentDetail['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}
                                                        >{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </x-forms.select>
                                                <x-common.icon
                                                    name="fas fa-warehouse"
                                                    class="is-small is-left"
                                                />
                                                <x-common.validation-error property="adjustment.{{ $loop->index }}.warehouse_id" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6">
                                        <x-forms.field>
                                            <x-forms.label for="adjustment[{{ $loop->index }}][reason]">
                                                Reason <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.textarea
                                                    name="adjustment[{{ $loop->index }}][reason]"
                                                    id="adjustment[{{ $loop->index }}][reason]"
                                                    class="pl-6"
                                                    placeholder="Describe reason for adjusting this product level"
                                                >
                                                    {{ $adjustmentDetail['reason'] ?? '' }}
                                                </x-forms.textarea>
                                                <x-common.icon
                                                    name="fas fa-edit"
                                                    class="is-large is-left"
                                                />
                                                <x-common.validation-error property="adjustment.{{ $loop->index }}.reason" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <x-common.button
                    tag="button"
                    type="button"
                    mode="button"
                    id="addNewAdjustmentForm"
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

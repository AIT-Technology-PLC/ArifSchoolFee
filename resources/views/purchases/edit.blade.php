@extends('layouts.app')

@section('title', 'Edit Purchase')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title=" Edit Purchase" />
        <form
            id="formOne"
            action="{{ route('purchases.update', $purchase->id) }}"
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
                                Purchase No <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    name="code"
                                    id="code"
                                    value="{{ $purchase->code }}"
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
                            <x-forms.label for="purchased_on">
                                Purchase Date <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="date"
                                    name="purchased_on"
                                    id="purchased_on"
                                    placeholder="mm/dd/yyyy"
                                    value="{{ $purchase->purchased_on->toDateString() }}"
                                />
                                <x-common.icon
                                    name="fas fa-calendar-day"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="purchased_on" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6 {{ userCompany()->isDiscountBeforeVAT() ? 'is-hidden' : '' }}">
                        <x-forms.field>
                            <x-forms.label for="discount">
                                Discount <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="number"
                                    id="discount"
                                    name="discount"
                                    placeholder="Discount in Percentage"
                                    value="{{ $purchase->discount * 100 ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-percent"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="discount" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="type">
                                Purchase Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="type"
                                    name="type"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Type</option>
                                    <option
                                        value="Local Purchase"
                                        @selected(!$purchase->isImported())
                                    >Local Purchase</option>
                                    <option
                                        value="Import"
                                        @selected($purchase->isImported())
                                    >Import</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-shopping-bag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="payment_type">
                                Payment Method <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="payment_type"
                                    name="payment_type"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Payment</option>
                                    <option
                                        value="Cash Payment"
                                        @selected($purchase->payment_type == 'Cash Payment')
                                    >Cash Payment</option>
                                    <option
                                        value="Credit Payment"
                                        @selected($purchase->payment_type == 'Credit Payment')
                                    >Credit Payment</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-credit-card"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="payment_type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="supplier_id">
                                Supplier <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="supplier_id"
                                    name="supplier_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option
                                            value="{{ $supplier->id }}"
                                            @selected($purchase->supplier_id == $supplier->id)
                                        >{{ $supplier->company_name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-address-card"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="supplier_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="customer_id">
                                Description <sup class="has-text-danger"> </sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="pl-6"
                                    placeholder="Description or note to be taken"
                                >
                                    {{ $purchase->description }}
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
            </x-content.main>

            @include('purchases.details-form', ['data' => ['purchase' => $purchase->purchaseDetails]])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

@extends('layouts.app')

@section('title')
    Edit Product
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit Product - {{ $product->name }}
            </h1>
        </div>
        <form id="formOne" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method("PATCH")
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <div class="field">
                            <label for="type" class="label text-green has-text-weight-normal"> Inventory Type <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="type" name="type">
                                        <x-inventory-type-options :type="$product->type" />
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                                @error('type')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="name" class="label text-green has-text-weight-normal">Name <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="name" name="name" type="text" class="input" placeholder="Product Name" value="{{ $product->name ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-boxes"></i>
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
                            <label for="code" class="label text-green has-text-weight-normal">Product Code <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="code" name="code" type="text" class="input" placeholder="Product Code" value="{{ $product->code ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                @error('code')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="product_category_id" class="label text-green has-text-weight-normal"> Product Category <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="product_category_id" name="product_category_id">
                                        <option selected disabled>Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $product->product_category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                @error('product_category_id')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="unit_of_measurement" class="label text-green has-text-weight-normal"> Unit of Measurement <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="unit_of_measurement" name="unit_of_measurement">
                                        <x-measurement-unit-options :selectedUnitType="$product->unit_of_measurement" />
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </div>
                                @error('unit_of_measurement')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="min_on_hand" class="label text-green has-text-weight-normal"> Minimum Level <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="min_on_hand" name="min_on_hand" type="number" class="input" placeholder="What is considered low stock for this product?" value="{{ $product->min_on_hand ?? '0.00' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-battery-quarter"></i>
                                </span>
                                @error('min_on_hand')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="supplier_id" class="label text-green has-text-weight-normal"> Product Supplier <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="supplier_id" name="supplier_id">
                                        <option selected disabled>Suppliers</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->company_name }}
                                            </option>
                                        @endforeach
                                        <option value="">None</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-address-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="field">
                            <label for="description" class="label text-green has-text-weight-normal">Description</label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="10" class="textarea pl-6" placeholder="Description or note about the new category">{{ $product->description ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-edit"></i>
                                </span>
                                @error('description')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns is-marginless is-multiline">
                    @if (is_null($product->properties))
                        {{ '' }}
                    @else
                        @foreach ($product->properties as $property)
                            <div class="column is-6">
                                <div class="field">
                                    <label for="properties[{{ $loop->index }}][{{ $property['key'] }}]" class="label text-green has-text-weight-normal">Property</label>
                                    <div class="control">
                                        <input id="properties[{{ $loop->index }}][{{ $property['key'] }}]" name="properties[{{ $loop->index }}][key]" type="text" class="input" value="{{ $property['key'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="properties[{{ $loop->index }}][{{ $property['value'] }}]" class="label text-green has-text-weight-normal">Data</label>
                                    <div class="control">
                                        <input id="properties[{{ $loop->index }}][{{ $property['value'] }}]" name="properties[{{ $loop->index }}][value]" type="text" class="input" value="{{ $property['value'] }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="box radius-top-0">
                <x-save-button />
            </div>
        </form>
    </section>
@endsection

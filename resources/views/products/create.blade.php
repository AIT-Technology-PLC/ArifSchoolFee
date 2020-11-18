@extends('layouts.app')

@section('title')
    Create New Product
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Product
            </h1>
        </div>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <div class="field">
                            <label for="type" class="label text-green has-text-weight-normal"> Inventory Type <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="type" name="type">
                                        <option selected disabled>Type</option>
                                        <option value="manufacturing" {{ old('type') == 'manufacturing' ? 'selected' : '' }}>Finished Product</option>
                                        <option value="merchandise" {{ old('type') == 'merchandise' ? 'selected' : '' }}>Merchandise Product</option>
                                        <option value="raw" {{ old('type') == 'raw' ? 'selected' : '' }}>Raw Material</option>
                                        <option value="mro" {{ old('type') == 'mro' ? 'selected' : '' }}>MRO Item</option>
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
                                <input id="name" name="name" type="text" class="input" placeholder="Product Name" value="{{ old('name') ?? '' }}">
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
                            <label for="product_category_id" class="label text-green has-text-weight-normal"> Product Category <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="product_category_id" name="product_category_id">
                                        <option selected disabled>Category</option>
                                        @foreach ($productCategories as $productCategory)
                                            <option value="{{ $productCategory->id }}" {{ old('product_category_id') == $productCategory->id ? 'selected' : '' }}> 
                                                {{ $productCategory->name }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="unit_of_measurement" class="label text-green has-text-weight-normal"> Unit of Measurement <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="unit_of_measurement" name="unit_of_measurement">
                                        <option selected disabled>Category</option>
                                        <option value="Kilogram" {{ old('unit_of_measurement') == "Kilogram" ? 'selected' : '' }}> Kilogram </option>
                                        <option class="Piece" {{ old('unit_of_measurement') == "Piece" ? 'selected' : '' }}> Piece </option>
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
                            <label for="selling_price" class="label text-green has-text-weight-normal">Selling Price <sup class="has-text-danger">*</sup> <sup class="has-text-grey is-size-7 is-uppercase"> per Kilogram </sup> </label>
                            <div class="control has-icons-left">
                                <input id="selling_price" name="selling_price" type="text" class="input" placeholder="Selling Price" value="{{ old('selling_price') ?? '0.00' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-tags"></i>
                                </span>
                                @error('selling_price')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="purchase_price" class="label text-green has-text-weight-normal"> Purchase Price <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="purchase_price" name="purchase_price" type="text" class="input" placeholder="Cost of Product" value="{{ old('purchase_price') ?? '0.00' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                @error('purchase_price')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="min_on_hand" class="label text-green has-text-weight-normal"> Minimum Level <sup class="has-text-danger">*</sup> <sup class="has-text-grey is-size-7 is-uppercase"> per Kilogram </sup> </label>
                            <div class="control has-icons-left">
                                <input id="min_on_hand" name="min_on_hand" type="text" class="input" placeholder="What is considered low stock for this product?" value="{{ old('min_on_hand') ?? '0' }}">
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
                            <label for="description" class="label text-green has-text-weight-normal">Description</label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="10" class="textarea pl-6" placeholder="Description or note about the new category">{{ old('description') ?? '' }}</textarea>
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
                    <div class="column is-6">
                        <div class="field">
                            <label for="is_expirable" class="label text-green has-text-weight-normal"> Does this product has expiry date? <sup class="has-text-danger">*</sup> </label>
                            <div class="control">
                                <label class="radio text-green has-text-weight-normal">
                                    <input type="radio" name="is_expirable" value="1" {{ old('is_expirable') ? 'checked' : '' }}>
                                    Yes
                                </label>
                                <label class="radio text-green has-text-weight-normal">
                                    <input type="radio" name="is_expirable" value="0" {{ old('is_expirable') ? '' : 'checked'  }}>
                                    No
                                </label>
                                @error('is_expirable')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="field mt-5">
                            <button id="addNewForm" class="button has-text-white bg-purple is-small" type="button">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Add More Forms
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="newForm" class="columns is-marginless is-multiline is-hidden"></div>
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

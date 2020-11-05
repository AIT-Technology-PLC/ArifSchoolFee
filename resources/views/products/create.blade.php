@extends('layouts.app')

@section('title')
Create New Product - Onrica Technologies PLC
@endsection

@section('content')
<section class="mt-3 mx-3">
    <div class="box radius-bottom-0 mb-0 has-background-white-bis">
        <h1 class="title text-green has-text-weight-medium is-size-5">
            New Product
        </h1>
    </div>
    <div class="box radius-bottom-0 mb-0 radius-top-0">
        <div class="columns is-marginless is-multiline">
            <div class="column is-12">
                <div class="field">
                    <label for="inventory" class="label text-green has-text-weight-normal"> Inventory Type <sup class="has-text-danger">*</sup> </label>
                    <div class="control has-icons-left">
                        <div class="select is-fullwidth">
                            <select id="inventory" name="inventory">
                                <option selected disabled>Type</option>
                                <option>Manufacturing</option>
                                <option>Merchandise</option>
                            </select>
                        </div>
                        <div class="icon is-small is-left">
                            <i class="fas fa-sitemap"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label for="name" class="label text-green has-text-weight-normal">Name <sup class="has-text-danger">*</sup> </label>
                    <div class="control has-icons-left">
                        <input id="name" name="name" type="text" class="input" placeholder="Product Name">
                        <span class="icon is-small is-left">
                            <i class="fas fa-boxes"></i>
                        </span>
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
                                <option>Select dropdown</option>
                                <option>With options</option>
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
                                <option>Select dropdown</option>
                                <option>With options</option>
                            </select>
                        </div>
                        <div class="icon is-small is-left">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label for="selling_price" class="label text-green has-text-weight-normal">Selling Price <sup class="has-text-danger">*</sup> <sup class="has-text-grey is-size-7 is-uppercase"> per Kilogram </sup> </label>
                    <div class="control has-icons-left">
                        <input id="selling_price" name="selling_price" type="number" class="input" placeholder="Selling Price">
                        <span class="icon is-small is-left">
                            <i class="fas fa-tags"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label for="purchase_price" class="label text-green has-text-weight-normal"> Cost <sup class="has-text-danger">*</sup> </label>
                    <div class="control has-icons-left">
                        <input id="purchase_price" name="purchase_price" type="number" class="input" placeholder="Cost of Product">
                        <span class="icon is-small is-left">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label for="min_on_hand" class="label text-green has-text-weight-normal"> Minimum Level <sup class="has-text-danger">*</sup> <sup class="has-text-grey is-size-7 is-uppercase"> per Kilogram </sup> </label>
                    <div class="control has-icons-left">
                        <input id="min_on_hand" name="min_on_hand" type="number" class="input" placeholder="What is considered low stock for this product?">
                        <span class="icon is-small is-left">
                            <i class="fas fa-battery-quarter"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label for="description" class="label text-green has-text-weight-normal">Description</label>
                    <div class="control has-icons-left">
                        <textarea name="description" id="description" cols="30" rows="10" class="textarea pl-6" placeholder="Description or note about the new category"></textarea>
                        <span class="icon is-large is-left">
                            <i class="fas fa-edit"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label for="is_expirable" class="label text-green has-text-weight-normal"> Does this product has expiry date? <sup class="has-text-danger">*</sup> </label>
                    <div class="control">
                        <label class="radio text-green has-text-weight-normal">
                            <input type="radio" name="is_expirable" value="1">
                            Yes
                        </label>
                        <label class="radio text-green has-text-weight-normal">
                            <input type="radio" name="is_expirable" value="0">
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="field mt-5">
                    <button class="button has-text-white bg-purple is-small">
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
    </div>
    <div class="box radius-top-0">
        <div class="columns is-marginless">
            <div class="column is-paddingless">
                <div class="buttons is-right">
                    <button class="button is-white text-green">
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
</section>
@endsection

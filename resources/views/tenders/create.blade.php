@extends('layouts.app')

@section('title')
    Create New Tender
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Tender
            </h1>
        </div>
        <form id="formOne" action="{{ route('tenders.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="notification bg-gold has-text-white has-text-weight-medium {{ session('message') ? '' : 'is-hidden' }}">
                    <span class="icon">
                        <i class="fas fa-times-circle"></i>
                    </span>
                    <span>
                        {{ session('message') }}
                    </span>
                </div>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="name" class="label text-green has-text-weight-normal">Project Name <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="name" id="name" placeholder="Project Name" value="{{ old('name') ?? '' }}">
                                <span class="icon is-large is-left">
                                    <i class="fas fa-business-time"></i>
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
                            <label for="type" class="label text-green has-text-weight-normal"> Type <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="type" name="type">
                                        <option selected disabled>Select Type</option>
                                        <option value="NCB" {{ old('type') == 'NCB' ? 'selected' : '' }}>NCB</option>
                                        <option value="ICB" {{ old('type') == 'ICB' ? 'selected' : '' }}>ICB</option>
                                    </select>
                                </div>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-columns"></i>
                                </span>
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
                            <label for="status" class="label text-green has-text-weight-normal"> Status <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="status" name="status">
                                        <option selected disabled>Select Status</option>
                                        <option value="Registered" {{ old('status') == 'Registered' ? 'selected' : '' }}>Registered</option>
                                        <option value="Lost" {{ old('status') == 'Lost' ? 'selected' : '' }}>Lost</option>
                                        <option value="Withdrawn" {{ old('status') == 'Withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                                        <option value="Won" {{ old('status') == 'Won' ? 'selected' : '' }}>Won</option>
                                        <option value="Submitted" {{ old('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                    </select>
                                </div>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-info"></i>
                                </span>
                                @error('status')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="participants" class="label text-green has-text-weight-normal">Participants <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="number" name="participants" id="participants" value="{{ old('participants') ?? '0' }}">
                                <span class="icon is-large is-left">
                                    <i class="fas fa-users"></i>
                                </span>
                                @error('participants')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="published_on" class="label text-green has-text-weight-normal"> Published On <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="published_on" id="published_on" placeholder="mm/dd/yyyy" value="{{ old('published_on') ?? '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                @error('published_on')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="closing_date" class="label text-green has-text-weight-normal"> Closing Date <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="closing_date" id="closing_date" placeholder="mm/dd/yyyy" value="{{ old('closing_date') ?? '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                @error('closing_date')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="opening_date" class="label text-green has-text-weight-normal"> Opening Date <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="opening_date" id="opening_date" placeholder="mm/dd/yyyy" value="{{ old('opening_date') ?? '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                @error('opening_date')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="customer_id" class="label text-green has-text-weight-normal"> Customer <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="customer_id" name="customer_id">
                                        <option selected disabled>Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->company_name }}</option>
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
                    <div class="column is-6">
                        <div class="field">
                            <label for="description" class="label text-green has-text-weight-normal"> Description <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ old('description') ?? '' }}</textarea>
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
                <p class="text-green has-text-weight-normal mx-3 mb-3 mt-4">
                    Select Checklists
                </p>
                <div class="box has-background-white-bis radius-top-0 mx-3 mb-6">
                    <div class="columns is-marginless is-multiline">
                        @foreach ($generalTenderChecklists as $generalTenderChecklist)
                            <div class="column is-one-fifth">
                                <div class="field">
                                    <div class="control">
                                        <label class="checkbox text-green has-text-weight-normal is-size-7">
                                            <input type="checkbox" name="checklists[{{ $loop->index }}][item]" value="{{ $generalTenderChecklist->id }}" {{ old('checklists.' . $loop->index . '.item') == $generalTenderChecklist->id ? 'checked' : '' }}>
                                            {{ $generalTenderChecklist->item }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('checklists')
                        <span class="help has-text-danger" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="has-text-weight-medium has-text-left mt-5">
                    <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                        Item 1
                    </span>
                </div>
                <div class="box has-background-white-bis radius-top-0">
                    <div name="tenderFormGroup" class="columns is-marginless is-multiline">
                        <div class="column is-6">
                            <div class="field">
                                <label for="tender[0][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <div class="select is-fullwidth">
                                        <select id="tender[0][product_id]" name="tender[0][product_id]" onchange="getProductSelected(this.id, this.value)">
                                            <option selected disabled>Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" {{ old('tender.0.product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-th"></i>
                                    </div>
                                    @error('tender.0.product_id')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="tender[0][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="tender[0][quantity]" name="tender[0][quantity]" type="number" class="input" placeholder="Quantity" value="{{ old('tender.0.quantity') ?? '0.00' }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-balance-scale"></i>
                                    </span>
                                    @error('tender.0.quantity')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="control">
                                    <button id="tender[0][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <label for="tender[0][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="tender[0][unit_price]" name="tender[0][unit_price]" type="number" class="input" placeholder="Unit Price" value="{{ old('tender.0.unit_price') ?? '0.00' }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-money-bill"></i>
                                    </span>
                                    @error('tender.0.unit_price')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="control">
                                    <button id="tender[0][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label for="tender[0][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                                <div class="control has-icons-left">
                                    <textarea name="tender[0][description]" id="tender[0][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ old('tender.0.description') ?? '' }}</textarea>
                                    <span class="icon is-large is-left">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    @error('tender.0.description')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @for ($i = 1; $i < 10; $i++)
                    @if (old('tender.' . $i . '.product_id') || old('tender.' . $i . '.quantity')) 
                        <div class="has-text-weight-medium has-text-left">
                            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                                Item {{ $i + 1 }}
                            </span>
                        </div>
                        <div class="box has-background-white-bis radius-top-0">
                            <div name="tenderFormGroup" class="columns is-marginless is-multiline">
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="tender[{{ $i }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                        <div class="control has-icons-left">
                                            <div class="select is-fullwidth">
                                                <select id="tender[{{ $i }}][product_id]" name="tender[{{ $i }}][product_id]" onchange="getProductSelected(this.id, this.value)">
                                                    <option selected disabled>Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ old('tender.' . $i . '.product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="icon is-small is-left">
                                                <i class="fas fa-th"></i>
                                            </div>
                                            @error('tender.' . $i . '.product_id')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <label for="tender[{{ $i }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                    <div class="field has-addons">
                                        <div class="control has-icons-left is-expanded">
                                            <input id="tender[{{ $i }}][quantity]" name="tender[{{ $i }}][quantity]" type="number" class="input" placeholder="Quantity" value="{{ old('tender.' . $i . '.quantity') ?? '0.00' }}">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-balance-scale"></i>
                                            </span>
                                            @error('tender.' . $i . '.quantity')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="control">
                                            <button id="tender[{{ $i }}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <label for="tender[{{ $i }}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                                    <div class="field has-addons">
                                        <div class="control has-icons-left is-expanded">
                                            <input id="tender[{{ $i }}][unit_price]" name="tender[{{ $i }}][unit_price]" type="number" class="input" placeholder="Unit Price" value="{{ old('tender.' . $i . '.unit_price') ?? '0.00' }}">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-money-bill"></i>
                                            </span>
                                            @error('tender.' . $i . '.unit_price')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="control">
                                            <button id="tender[{{ $i }}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label for="tender[{{ $i }}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                                        <div class="control has-icons-left">
                                            <textarea name="tender[{{ $i }}][description]" id="tender[{{ $i }}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ old('tender.' . $i . '.description') ?? '' }}</textarea>
                                            <span class="icon is-large is-left">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                            @error('tender.' . $i . '.description')
                                                <span class="help has-text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @break
                    @endif
                @endfor
                <div id="tenderFormWrapper"></div>
                <button id="addNewTenderForm" type="button" class="button bg-purple has-text-white is-small ml-3 mt-3">
                    Add More Item
                </button>
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
                            <button id="saveButton" class="button bg-green has-text-white">
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

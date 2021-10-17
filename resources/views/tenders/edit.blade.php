@extends('layouts.app')

@section('title')
    Edit Tender
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Edit Tender
            </h1>
        </div>
        <form id="formOne" action="{{ route('tenders.update', $tender->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
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
                            <label for="code" class="label text-green has-text-weight-normal">Tender No <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="code" id="code" placeholder="Tender No" value="{{ $tender->code ?? '' }}">
                                <span class="icon is-large is-left">
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
                            <label for="type" class="label text-green has-text-weight-normal"> Type <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="type" name="type">
                                        <option selected disabled>Select Type</option>
                                        <option value="NCB" {{ $tender->type == 'NCB' ? 'selected' : '' }}>NCB</option>
                                        <option value="ICB" {{ $tender->type == 'ICB' ? 'selected' : '' }}>ICB</option>
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
                                        @foreach ($tenderStatuses as $tenderStatus)
                                            <option value="{{ $tenderStatus->status }}" {{ $tender->status == $tenderStatus->status ? 'selected' : '' }}>{{ $tenderStatus->status }}</option>
                                        @endforeach
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
                            <label for="participants" class="label text-green has-text-weight-normal">Participants <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="number" name="participants" id="participants" value="{{ $tender->participants ?? '' }}">
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
                        <label for="bid_bond_type" class="label text-green has-text-weight-normal"> Bid Bond <span class="has-text-weight-light is-size-7">(Type, Amount, Validity)</span> <sup class="has-text-danger"></sup> </label>
                        <div class="field has-addons">
                            <p class="control">
                                <input name="bid_bond_type" class="input" type="text" placeholder="Type" value="{{ $tender->bid_bond_type ?? '' }}">
                            </p>
                            <p class="control">
                                <input name="bid_bond_amount" class="input has-background-white-ter" type="text" placeholder="Amount" value="{{ $tender->bid_bond_amount ?? '' }}">
                            </p>
                            <p class="control">
                                <input name="bid_bond_validity" class="input" type="text" placeholder="Validity" value="{{ $tender->bid_bond_validity ?? '' }}">
                            </p>
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
                                            <option value="{{ $customer->id }}" {{ $tender->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->company_name }}</option>
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
                            <label for="price" class="label text-green has-text-weight-normal"> Price <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea name="price" id="price" cols="30" rows="3" class="textarea pl-6" placeholder="Price Description">{{ $tender->price ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                                @error('price')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="payment_term" class="label text-green has-text-weight-normal"> Payment Term <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea name="payment_term" id="payment_term" cols="30" rows="3" class="textarea pl-6" placeholder="Payment Term">{{ $tender->payment_term ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </span>
                                @error('payment_term')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="description" class="label text-green has-text-weight-normal"> Description <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ $tender->description ?? '' }}</textarea>
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
                <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3 mx-3 mt-5">
                    <h1 class="text-green is-size-5 is-uppercase has-text-weight-semibold has-text-centered">
                        Tender Schedules
                    </h1>
                </div>
                <div class="box is-radiusless mx-3">
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-6">
                            <div class="field">
                                <label for="published_on" class="label text-green has-text-weight-normal"> Published On <sup class="has-text-danger">*</sup> </label>
                                <div class="control has-icons-left">
                                    <input class="input" type="date" name="published_on" id="published_on" placeholder="mm/dd/yyyy" value="{{ $tender->published_on->toDateString() ?? '' }}">
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
                                    <input class="input" type="datetime-local" name="closing_date" id="closing_date" placeholder="mm/dd/yyyy" value="{{ $tender->closing_date->toDateTimeLocalString() ?? '' }}">
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
                                    <input class="input" type="datetime-local" name="opening_date" id="opening_date" placeholder="mm/dd/yyyy" value="{{ $tender->opening_date->toDateTimeLocalString() ?? '' }}">
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
                                <label for="clarify_on" class="label text-green has-text-weight-normal"> Clarification Date <sup class="has-text-danger"></sup> </label>
                                <div class="control has-icons-left">
                                    <input class="input" type="date" name="clarify_on" id="clarify_on" placeholder="mm/dd/yyyy" value="{{ $tender->clarify_on ? $tender->clarify_on->toDateString() : '' }}">
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('clarify_on')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label for="visit_on" class="label text-green has-text-weight-normal"> Visiting Date <sup class="has-text-danger"></sup> </label>
                                <div class="control has-icons-left">
                                    <input class="input" type="date" name="visit_on" id="visit_on" placeholder="mm/dd/yyyy" value="{{ $tender->visit_on ? $tender->visit_on->toDateString() : '' }}">
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('visit_on')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label for="premeet_on" class="label text-green has-text-weight-normal"> Pre-meeting Date <sup class="has-text-danger"></sup> </label>
                                <div class="control has-icons-left">
                                    <input class="input" type="date" name="premeet_on" id="premeet_on" placeholder="mm/dd/yyyy" value="{{ $tender->premeet_on ? $tender->premeet_on->toDateString() : '' }}">
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('premeet_on')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($tender->tenderDetails as $tenderDetail)
                    <div class="has-text-weight-medium has-text-left mt-5">
                        <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                            Item {{ $loop->index + 1 }}
                        </span>
                    </div>
                    <div class="box has-background-white-bis radius-top-0">
                        <div name="tenderFormGroup" class="columns is-marginless is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label for="tender[{{ $loop->index }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                    <div class="control has-icons-left">
                                        <x-common.product-list tags="false" name="tender[{{ $loop->index }}]" selected-product-id="{{ $tenderDetail->product->id }}" />
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-th"></i>
                                        </div>
                                        @error('tender.' . $loop->index . '.product_id')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label for="tender[{{ $loop->index }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                <div class="field has-addons">
                                    <div class="control has-icons-left is-expanded">
                                        <input id="tender[{{ $loop->index }}][quantity]" name="tender[{{ $loop->index }}][quantity]" type="number" class="input" placeholder="Quantity" value="{{ $tenderDetail->quantity ?? '0.00' }}">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-balance-scale"></i>
                                        </span>
                                        @error('tender.' . $loop->index . '.quantity')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="control">
                                        <button id="tender[{{ $loop->index }}][product_id]Quantity" class="button bg-green has-text-white" type="button">{{ $tenderDetail->product->unit_of_measurement }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label for="tender[{{ $loop->index }}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                                    <div class="control has-icons-left">
                                        <textarea name="tender[{{ $loop->index }}][description]" id="tender[{{ $loop->index }}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ $tenderDetail->description ?? '' }}</textarea>
                                        <span class="icon is-large is-left">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        @error('tender.' . $loop->index . '.description')
                                            <span class="help has-text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="box radius-top-0">
                <x-common.save-button />
            </div>
        </form>
    </section>
@endsection

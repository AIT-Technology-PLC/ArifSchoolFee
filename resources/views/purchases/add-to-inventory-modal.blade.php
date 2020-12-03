<div id="addToInventoryModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head bg-green">
            <p class="modal-card-title has-text-white is-uppercase is-size-5 has-text-weight-medium">
                <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <span>
                    Add Purchased Products to Inventory
                </span>
            </p>
            <button id="closeModal" class="delete is-medium" aria-label="close"></button>
        </header>
        <form action="{{ route('merchandises.addToInventory', $purchase->id) }}" method="post">
            @csrf
            <section class="modal-card-body py-6">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <div class="field">
                            <label for="warehouse_id" class="label text-green has-text-weight-normal"> Where do you want to add your purchased products ? <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="warehouse_id" name="warehouse_id">
                                        <option selected disabled>Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                                @error('warehouse_id')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="field">
                            <label for="expires_on" class="label text-green has-text-weight-normal"> Expiry Date <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="expires_on" id="expires_on" value="{{ old('expires_on') ?? '' }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button id="addToInventoryNotNow" class="button is-light text-green" type="button">
                    <span>
                        Not now
                    </span>
                </button>
                <button class="button bg-green has-text-white">
                    <span class="icon">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span>
                        Add To Inventory
                    </span>
                </button>
            </footer>
        </form>
    </div>
</div>

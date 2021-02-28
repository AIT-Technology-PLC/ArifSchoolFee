<div id="createMenu" class="box is-hidden" style="position: absolute;top:100%;left:60%;width:350px;">
    <h1 class="is-size-7 has-text-centered is-uppercase has-text-weight-medium mb-2">
        <span class="icon">
            <i class="fas fa-plus-circle"></i>
        </span>
        <span>
            Create New
        </span>
    </h1>
    <div class="columns is-marginless is-multiline is-centered">
        <div class="column is-3 has-text-centered text-green">
            <a href="{{ route('gdns.create') }}" class="button is-rounded text-green" data-title="Create New Product">
                <span class="icon">
                    <i class="fas fa-file-invoice"></i>
                </span>
            </a>
            <span class="is-size-7">
                DO/GDN
            </span>
        </div>
        <div class="column is-3 has-text-centered text-purple">
            <a href="{{ route('grns.create') }}" class="button is-rounded text-purple" data-title="Create New Supplier">
                <span class="icon">
                    <i class="fas fa-file-contract"></i>
                </span>
            </a>
            <span class="is-size-7">
                GRN
            </span>
        </div>
        <div class="column is-3 has-text-centered text-blue">
            <a href="{{ route('transfers.create') }}" class="button is-rounded text-blue" data-title="Create New Category">
                <span class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </span>
            </a>
            <span class="is-size-7">
                Transfer
            </span>
        </div>
        <div class="column is-3 has-text-centered text-gold">
            <a href="{{ route('sales.create') }}" class="button is-rounded text-gold" data-title="Create New Sale">
                <span class="icon">
                    <i class="fas fa-tags"></i>
                </span>
            </a>
            <span class="is-size-7">
                Sale
            </span>
        </div>
        <div class="column is-3 has-text-centered text-gold">
            <a href="{{ route('purchase-orders.create') }}" class="button is-rounded text-gold" data-title="Create New Purchase">
                <span class="icon">
                    <i class="fas fa-file-alt"></i>
                </span>
            </a>
            <span class="is-size-7">
                PO
            </span>
        </div>
        <div class="column is-3 has-text-centered text-blue">
            <a href="{{ route('purchases.create') }}" class="button is-rounded text-blue" data-title="Create New Employee">
                <span class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </span>
            </a>
            <span class="is-size-7">
                Purchase
            </span>
        </div>
        <div class="column is-3 has-text-centered text-purple">
            <a href="{{ route('suppliers.create') }}" class="button is-rounded text-purple" data-title="Create New Warehouse">
                <span class="icon">
                    <i class="fas fa-address-card"></i>
                </span>
            </a>
            <span class="is-size-7">
                Supplier
            </span>
        </div>
        <div class="column is-3 has-text-centered text-green">
            <a href="{{ route('customers.create') }}" class="button is-rounded text-green" data-title="Create New Customer">
                <span class="icon">
                    <i class="fas fa-user"></i>
                </span>
            </a>
            <span class="is-size-7">
                Customer
            </span>
        </div>
    </div>
</div>

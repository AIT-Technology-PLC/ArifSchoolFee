<div id="createMenu" class="box is-hidden" style="position: absolute;top:100%;left:70%;width:300px;">
    <h1 class="is-size-7 has-text-centered is-uppercase has-text-weight-medium mb-2">
        <span class="icon">
            <i class="fas fa-plus-circle"></i>
        </span>
        <span>
            Create New
        </span>
    </h1>
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 has-text-centered text-green">
            <a href="{{ route('products.create') }}" class="button is-rounded text-green" title="Create New Product">
                <span class="icon">
                    <i class="fas fa-boxes"></i>
                </span>
            </a>
            <span class="is-size-7">
                Product
            </span>
        </div>
        <div class="column is-4 has-text-centered text-purple">
            <a href="{{ route('suppliers.create') }}" class="button is-rounded text-purple" title="Create New Supplier">
                <span class="icon">
                    <i class="fas fa-address-card"></i>
                </span>
            </a>
            <span class="is-size-7">
                Supplier
            </span>
        </div>
        <div class="column is-4 has-text-centered text-gold">
            <a href="{{ route('categories.create') }}" class="button is-rounded text-gold" title="Create New Category">
                <span class="icon">
                    <i class="fas fa-layer-group"></i>
                </span>
            </a>
            <span class="is-size-7">
                Category
            </span>
        </div>
        <div class="column is-4 has-text-centered text-purple">
            <a href="{{ route('purchases.create') }}" class="button is-rounded text-purple" title="Create New Purchase">
                <span class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </span>
            </a>
            <span class="is-size-7">
                Purchase
            </span>
        </div>
        <div class="column is-4 has-text-centered text-gold">
            <a href="{{ route('employees.create') }}" class="button is-rounded text-gold" title="Create New Employee">
                <span class="icon">
                    <i class="fas fa-user-tie"></i>
                </span>
            </a>
            <span class="is-size-7">
                Employee
            </span>
        </div>
        <div class="column is-4 has-text-centered text-blue">
            <a href="{{ route('warehouses.create') }}" class="button is-rounded text-blue" title="Create New Warehouse">
                <span class="icon">
                    <i class="fas fa-warehouse"></i>
                </span>
            </a>
            <span class="is-size-7">
                Warehouse
            </span>
        </div>
    </div>
</div>

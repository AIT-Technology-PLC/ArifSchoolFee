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
            <a href="{{ route('gdns.create') }}" class="button is-rounded text-green" data-title="Create New DO/GDN">
                <span class="icon">
                    <i class="fas fa-file-invoice"></i>
                </span>
            </a>
            <span class="is-size-7">
                DO/GDN
            </span>
        </div>
        <div class="column is-3 has-text-centered text-purple">
            <a href="{{ route('grns.create') }}" class="button is-rounded text-purple" data-title="Create New GRN">
                <span class="icon">
                    <i class="fas fa-file-contract"></i>
                </span>
            </a>
            <span class="is-size-7">
                GRN
            </span>
        </div>
        <div class="column is-3 has-text-centered text-blue">
            <a href="{{ route('transfers.create') }}" class="button is-rounded text-blue" data-title="Create New Transfer">
                <span class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </span>
            </a>
            <span class="is-size-7">
                Transfer
            </span>
        </div>
        @if (userCompany()->name != 'AE Chemicals Trading PLC')
            <div class="column is-3 has-text-centered text-gold">
                <a href="{{ route('sivs.create') }}" class="button is-rounded text-gold" data-title="Create New SIV">
                    <span class="icon">
                        <i class="fas fa-file-export"></i>
                    </span>
                </a>
                <span class="is-size-7">
                    SIV
                </span>
            </div>
        @endif
        <div class="column is-3 has-text-centered text-gold">
            <a href="{{ route('tenders.create') }}" class="button is-rounded text-gold" data-title="Create New Tender">
                <span class="icon">
                    <i class="fas fa-project-diagram"></i>
                </span>
            </a>
            <span class="is-size-7">
                Tender
            </span>
        </div>
        <div class="column is-3 has-text-centered text-blue">
            <a href="{{ route('purchases.create') }}" class="button is-rounded text-blue" data-title="Create New Purchase">
                <span class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </span>
            </a>
            <span class="is-size-7">
                Purchase
            </span>
        </div>
        <div class="column is-3 has-text-centered text-purple">
            <a href="{{ route('suppliers.create') }}" class="button is-rounded text-purple" data-title="Create New Supplier">
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

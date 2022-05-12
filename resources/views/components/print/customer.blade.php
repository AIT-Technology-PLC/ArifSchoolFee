@props([
    'customer' => $customer,
])

@if ($customer)
    <section class="is-clearfix has-background-white-bis py-3 pl-6 pr-6">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                Customer
            </h1>
            <p class="has-text-black is-size-6">
                {{ $customer->company_name }}
            </p>
        </aside>
        @if ($customer->tin)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    TIN
                </h1>
                <p class="has-text-black is-size-6">
                    {{ $customer->tin }}
                </p>
            </aside>
        @endif
        @if ($customer->contact_name)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    Contact
                </h1>
                <p class="has-text-black is-size-6">
                    {{ $customer->contact_name }}
                </p>
            </aside>
        @endif
        @if ($customer->address)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    Location
                </h1>
                <p class="has-text-black is-size-6">
                    {{ $customer->address }}
                </p>
            </aside>
        @endif
    </section>
@endif

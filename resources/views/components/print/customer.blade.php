@props([
    'customer' => $customer,
])

@if ($customer)
    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Customer
            </h1>
            <p class="has-text-black is-size-6 pr-2">
                {{ $customer->company_name }}
            </p>
        </aside>
        @if ($customer->tin)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                    TIN
                </h1>
                <p class="has-text-black is-size-6 pr-2">
                    {{ $customer->tin }}
                </p>
            </aside>
        @endif
        @if ($customer->contact_name)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                    Contact
                </h1>
                <p class="has-text-black is-size-6 pr-2">
                    {{ $customer->contact_name }}
                </p>
            </aside>
        @endif
        @if ($customer->address)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                    Location
                </h1>
                <p class="has-text-black is-size-6 pr-2">
                    {{ $customer->address }}
                </p>
            </aside>
        @endif
    </section>
    <hr
        class="my-0 has-background-white-ter"
        style="margin-left: -10%;margin-right: -10%"
    >
@endif

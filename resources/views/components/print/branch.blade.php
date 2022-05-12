@props(['warehouse' => $warehouse])

@if (userCompany()->canShowBranchDetailOnPrint())
    <section class="is-clearfix has-background-white-ter py-3 pl-6 pr-6">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                Branch
            </h1>
            <p class="has-text-black is-size-6">
                {{ $warehouse->name }}
            </p>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                Location
            </h1>
            <p class="has-text-black is-size-6">
                {{ $warehouse->location }}
            </p>
        </aside>
        @if ($warehouse->phone)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    Tel/Phone
                </h1>
                <p class="has-text-black is-size-6">
                    {{ $warehouse->phone }}
                </p>
            </aside>
        @endif
        @if ($warehouse->email)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    Email
                </h1>
                <p class="has-text-black is-size-6">
                    {{ $warehouse->email }}
                </p>
            </aside>
        @endif
    </section>
@endif

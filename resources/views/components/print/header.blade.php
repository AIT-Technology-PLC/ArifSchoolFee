@props([
    'warehouse' => $warehouse,
])

<header class="is-clearfix py-4">
    <aside class="is-pulled-left ml-6">
        <img
            src="{{ asset('storage/' . userCompany()->logo) }}"
            style="width: 180px !important; height: 78px !important"
        >
    </aside>
    <aside
        class="is-pulled-right"
        style="width: 500px !important;"
    >
        <h1 class="is-uppercase has-text-black has-text-weight-bold is-size-4">
            {{ userCompany()->name }}
        </h1>
        <p class="has-text-grey has-text-weight-medium">
            {!! collect([$warehouse->location ?? userCompany()->address, $warehouse->email ?? userCompany()->email, $warehouse->phone ?? userCompany()->phone])->filter()->join(' ' . htmlspecialchars_decode('&middot;') . ' ') !!}
        </p>
        @if (userCompany()->canShowBranchDetailOnPrint())
            <p class="has-text-grey has-text-weight-medium">
                {{ $warehouse->name }}
            </p>
        @endif
    </aside>
</header>

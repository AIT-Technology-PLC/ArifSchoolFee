@props([
    'warehouse' => $warehouse,
])

<header class="has-text-centered mb-2">
    <aside class="pt-4 pb-2">
        <img
            src="{{ asset('storage/' . userCompany()->logo) }}"
            style="width: 180px !important; height: 78px !important"
        >
    </aside>
    <aside>
        <h1 class="is-capitalized has-text-black has-text-weight-bold is-size-5">
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

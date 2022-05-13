@props([
    'warehouse' => null,
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
        @if ($warehouse && userCompany()->canShowBranchDetailOnPrint())
            <p class="has-text-grey has-text-weight-medium">
                {!! collect([$warehouse->location ?? userCompany()->address, $warehouse->email ?? userCompany()->email, $warehouse->phone ?? userCompany()->phone])->filter()->join(' ' . htmlspecialchars_decode('&middot;') . ' ') !!}
            </p>
            <p class="has-text-grey has-text-weight-medium">
                {{ $warehouse->name }}
            </p>
        @else
            <p class="has-text-grey has-text-weight-medium">
                {!! collect([userCompany()->address, userCompany()->email, userCompany()->phone])->filter()->join(' ' . htmlspecialchars_decode('&middot;') . ' ') !!}
            </p>
        @endif
    </aside>
</header>

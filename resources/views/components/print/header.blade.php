@props([
    'warehouse' => null,
])

<header class="is-clearfix py-4">
    @if (!is_null(userCompany()->logo))
        <aside class="is-pulled-left">
            <img
                src="{{ asset('storage/' . userCompany()->logo) }}"
                style="width: 180px !important; height: 78px !important"
            >
        </aside>
    @endif
    <aside
        class="{{ is_null(userCompany()->logo) ? 'is-pulled-left' : 'is-pulled-right' }}"
        style="width: {{ is_null(userCompany()->logo) ? '100%' : '500px' }} !important;"
    >
        <h1 class="is-uppercase has-text-black has-text-weight-bold is-size-4">
            {{ userCompany()->name }}
        </h1>
        @if ($warehouse && userCompany()->canShowBranchDetailOnPrint())
            <p class="has-text-grey has-text-weight-medium">
                {!! collect([$warehouse->email ?? userCompany()->email, $warehouse->phone ?? userCompany()->phone])->filter()->join(' ' . htmlspecialchars_decode('&middot;') . ' ') !!}
            </p>
            <p class="has-text-grey has-text-weight-medium">
                {!! collect([$warehouse->location ?? userCompany()->address, userCompany()->tin])->filter()->join(' ' . htmlspecialchars_decode('&middot;') . ' ') !!}
            </p>
        @else
            <p class="has-text-grey has-text-weight-medium">
                {!! collect([userCompany()->email, userCompany()->phone])->filter()->join(' ' . htmlspecialchars_decode('&middot;') . ' ') !!}
            </p>
            <p class="has-text-grey has-text-weight-medium">
                {!! collect([userCompany()->address, userCompany()->tin])->filter()->join(' ' . htmlspecialchars_decode('&middot;') . ' ') !!}
            </p>
        @endif
    </aside>
</header>

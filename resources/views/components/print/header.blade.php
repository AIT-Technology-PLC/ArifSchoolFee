<header class="is-clearfix">
    <aside
        class="is-pulled-left ml-6"
        style="padding-top: 10px !important;"
    >
        <img
            src="{{ asset('storage/' . userCompany()->logo) }}"
            style="width: 180px !important; height: 78px !important"
        >
    </aside>
    <aside
        class="is-pulled-right mr-6"
        style="width: 450px !important;padding-top: 10px !important;"
    >
        <h1 class="is-capitalized has-text-black has-text-weight-bold is-size-5">
            {{ userCompany()->name }}
        </h1>
        <p class="has-text-grey has-text-weight-medium">
            {{ userCompany()->phone ?? '-' }} -
            {{ userCompany()->email ?? '-' }} -
            {{ userCompany()->address ?? '-' }}
        </p>
    </aside>
</header>

<x-common.action-buttons
    :buttons="['details']"
    model="admin.companies"
    :id="$company->id"
/>

<x-common.button
    tag="a"
    href="{{ route('admin.companies.report', $company->id) }}"
    mode="button"
    data-title="Report"
    icon="fas fa-chart-pie"
    class="text-blue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

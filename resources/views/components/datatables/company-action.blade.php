<x-common.action-buttons
    :buttons="['details']"
    model="admin.schools"
    :id="$school->id"
/>

<x-common.button
    tag="a"
    href="{{ route('admin.schools.report', $school->id) }}"
    mode="button"
    data-title="Report"
    icon="fas fa-chart-pie"
    class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

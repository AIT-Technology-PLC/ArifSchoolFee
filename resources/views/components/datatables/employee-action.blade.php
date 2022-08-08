<x-common.button
    tag="a"
    href="{{ route('permissions.edit', $employee->id) }}"
    mode="button"
    data-title="Permissions"
    icon="fas fa-lock"
    class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

<x-common.action-buttons
    :buttons="['delete', 'edit']"
    model="employees"
    :id="$employee->id"
/>

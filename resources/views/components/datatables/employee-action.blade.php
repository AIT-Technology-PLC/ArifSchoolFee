<x-common.button
    tag="a"
    href="{{ route('users.show', $employee->id) }}"
    mode="button"
    data-title="Profile"
    icon="fas fa-circle-user"
    class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

<x-common.button
    tag="a"
    href="{{ route('permissions.edit', $employee->id) }}"
    mode="button"
    data-title="Permissions"
    icon="fas fa-lock"
    class="text-softblue has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

<x-common.action-buttons
    :buttons="['edit', 'delete']"
    model="users"
    :id="$employee->id"
/>

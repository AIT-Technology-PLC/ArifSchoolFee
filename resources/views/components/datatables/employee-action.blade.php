<a
    href="{{ route('permissions.edit', $employee->id) }}"
    data-title="Modify Employee Permissions"
>
    <span class="tag is-white btn-purple is-outlined is-small text-purple has-text-weight-medium">
        <span class="icon">
            <i class="fas fa-lock"></i>
        </span>
        <span>
            Permissions
        </span>
    </span>
</a>
<a
    href="{{ route('employees.edit', $employee->id) }}"
    data-title="Modify Employee Data"
>
    <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
        <span class="icon">
            <i class="fas fa-pen-square"></i>
        </span>
        <span>
            Edit
        </span>
    </span>
</a>
<x-common.delete-button
    route="employees.destroy"
    :id="$employee->id"
/>

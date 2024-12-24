<label class="checkbox mr-3 has-text-grey has-text-weight-light">
    <input
        type="checkbox"
        name="student_id[]"
        class="student-checkbox"
        value="{{ $student->id }}"
        data-student-id="{{ $student->id }}"
    >
</label>

<script>
    $(document).ready(function() {
        const selectAllCheckbox = document.querySelector('.select-all-checkbox');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');

        if (selectAllCheckbox && studentCheckboxes.length > 0) {
            // Ensure the "Select All" checkbox state is correctly set on page load
            const allChecked = [...studentCheckboxes].every(checkbox => checkbox.checked);
            const someChecked = [...studentCheckboxes].some(checkbox => checkbox.checked);

            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = !allChecked && someChecked;

            // Add event listener for the 'Select All' checkbox
            selectAllCheckbox.addEventListener('change', function() {
                studentCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

            // Update the 'Select All' checkbox when any individual checkbox is toggled
            studentCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const allChecked = [...studentCheckboxes].every(checkbox => checkbox.checked);
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = !allChecked && [...studentCheckboxes].some(checkbox => checkbox.checked);
                });
            });
        } else {
            console.error("Elements not found");
        }
    });
</script>
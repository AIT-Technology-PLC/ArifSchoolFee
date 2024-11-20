<label class="checkbox mr-3 has-text-grey has-text-weight-light">
    <input
        type="checkbox"
        name="student_id[]"
        class="student-checkbox"
        value="{{ $student->id }}"
        data-student-id="{{ $student->id }}"
        {{ $student->isAssignedToFeeMaster(request()->route('assign_fee')->id) ? 'checked' : '' }}
    >
</label>

<script>
    $(document).ready(function() {
        // Initialize the DataTable
        var table = $('.datatable').DataTable({
            columnDefs: [{
                targets: 0, // Target the first column (checkboxes)
                orderable: false, // Disable ordering for this column
                render: function(data, type, row) {
                    return ''; // Leave it empty, since checkboxes are already in the Blade component
                }
            }]
        });

        // Append the "select all" checkbox to the header
        $('#datatable thead th.dt-checkboxes').html('<input type="checkbox" id="select-all">');

        // Handle the "select all" checkbox in the header
        $('#datatable thead').on('change', '#select-all', function() {
            var isChecked = $(this).prop('checked');
            // Select or deselect all checkboxes in the table body
            table.rows().every(function() {
                this.node().querySelector('input[type="checkbox"]').checked = isChecked;
            });
        });

        // Update the "select all" checkbox based on individual row checkboxes
        $('#datatable tbody').on('change', 'input[type="checkbox"]', function() {
            var totalChecked = table.$('input[type="checkbox"]:checked').length;
            var totalCheckboxes = table.$('input[type="checkbox"]').length;

            // If all checkboxes are checked, check the "select all" checkbox
            $('#select-all').prop('checked', totalChecked === totalCheckboxes);
        });
    });
</script>
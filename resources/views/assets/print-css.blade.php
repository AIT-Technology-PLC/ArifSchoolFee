<style>
    .company-background {
        background: url({{ asset('storage/' . userCompany()->print_template_image) }}) no-repeat center center fixed;
        background-size: cover;
    }

    .company-x-padding {
        padding-right: {{ userCompany()->print_padding_horizontal }}%;
        padding-left: {{ userCompany()->print_padding_horizontal }}%;
    }

    .company-y-padding {
        padding-top: {{ userCompany()->print_padding_top }}%;
        padding-bottom: {{ userCompany()->print_padding_bottom }}%;
    }

    @media print {
        .table-breaked {
            page-break-before: auto;
        }
    }

    .summernote-table table td,
    .summernote-table table th {
        border-width: 1px !important;
        padding: 0 !important;
    }

    td {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }
</style>

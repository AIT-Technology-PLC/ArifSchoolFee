<style>
    .company-background {
        background: url({{ asset('storage/' . ($company?->print_template_image ?? userCompany()->print_template_image)) }}) no-repeat center center fixed;
        background-size: cover;
    }

    .company-x-padding {
        padding-right: {{ $company?->print_padding_horizontal ?? userCompany()->print_padding_horizontal }}%;
        padding-left: {{ $company?->print_padding_horizontal ?? userCompany()->print_padding_horizontal }}%;
    }

    .company-y-padding {
        padding-top: {{ $company?->print_padding_top ?? userCompany()->print_padding_top }}%;
        padding-bottom: {{ $company?->print_padding_bottom ?? userCompany()->print_padding_bottom }}%;
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

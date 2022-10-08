@props(['action', 'buttons' => 'none'])

<div class="mx-3 m-lr-0">
    <form
        method="GET"
        enctype="multipart/form-data"
        novalidate
        action="{{ $action }}"
    >
        @method('GET')
        <div
            id="quickviewDefault"
            class="quickview"
        >
            <header class="quickview-header">
                <div>
                    <h6 class="text-green has-text-centered has-text-weight-bold">
                        <span class="icon">
                            <i class="fas fa-filter"></i>
                        </span>
                        <span> Filters </span>
                    </h6>
                </div>
                <span
                    class="delete"
                    data-dismiss="quickview"
                ></span>
            </header>
            {{ $slot }}
            <footer class="quickview-footer is-justify-content-end">
                <div>
                    <x-common.button
                        tag="button"
                        mode="button"
                        type="reset"
                        label="Clear Filters"
                        x-on:click="location.search=''"
                        class="is-small btn-green is-outlined is-rounded"
                    />
                    <x-common.button
                        tag="button"
                        mode="button"
                        label="Apply"
                        class="is-small bg-green has-text-white is-rounded"
                    />
                </div>
            </footer>
        </div>
    </form>
</div>
<div class="mx-3 m-lr-0">
    <x-common.button
        tag="button"
        mode="button"
        data-show="quickview"
        data-target="quickviewDefault"
        label="Filters"
        icon="fas fa-filter"
        class="button btn-green is-outlined has-text-weight-medium is-size-7-mobile"
    />

    @if ($buttons == 'all' || in_array('export', $buttons))
        <x-common.button
            tag="a"
            mode="button"
            label="Export"
            icon="fas fa-download"
            href="{{ route('reports.sale_export', request()->query()) }}"
            class="button btn-purple is-outlined has-text-weight-medium is-size-7-mobile"
        />
    @endif
</div>

@push('scripts')
    <script
        type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/bulma-quickview@2.0.0/dist/js/bulma-quickview.min.js"
    ></script>
    <script type="text/javascript">
        var quickviews = bulmaQuickview.attach();
        $('#period').daterangepicker({
            "autoApply": true,
            "showDropdowns": true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'This Week': [moment().startOf('week'), moment().endOf('week')],
                'Last Week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            },
            "locale": {
                "format": "YYYY/MM/DD",
                "firstDay": 1
            },
            "alwaysShowCalendars": true,
        });
    </script>
@endpush

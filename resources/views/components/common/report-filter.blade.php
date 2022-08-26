<div class="mx-3">
    <form method="get">
        <div class="box">
            <div class="level mb-2">
                <div class="level-left">
                    <div class="level-item">
                        <div>
                            <h6 class="text-green has-text-centered has-text-weight-bold">
                                <span class="icon">
                                    <i class="fas fa-filter"></i>
                                </span>
                                <span> Filters </span>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <div class="is-hidden-mobile">
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
                    </div>
                </div>
            </div>
            <div class="columns is-marginless is-vcentered">
                {{ $slot }}
            </div>
            <div class="buttons is-centered my-4 is-hidden-tablet">
                <x-common.button
                    tag="button"
                    mode="button"
                    type="reset"
                    label="Clear Filters"
                    class="is-small btn-green is-outlined is-rounded"
                />
                <x-common.button
                    tag="button"
                    mode="button"
                    label="Apply"
                    class="is-small bg-green has-text-white is-rounded"
                />
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script type="text/javascript">
        $('#period').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "locale": {
                "format": "YYYY/MM/DD"
            },
            "alwaysShowCalendars": true,
            "startDate": moment().subtract(6, 'days'),
            "endDate": moment()
        });
    </script>
@endpush

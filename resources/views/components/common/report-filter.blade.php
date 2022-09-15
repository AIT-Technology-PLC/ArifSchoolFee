@props(['action'])

<div class="mx-3 m-lr-0">
    <form
        method="GET"
        enctype="multipart/form-data"
        novalidate
        action="{{ $action }}"
    >
        @method('GET')
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
            {{ $slot }}
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

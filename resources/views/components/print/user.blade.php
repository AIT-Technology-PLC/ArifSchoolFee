@props(['createdBy', 'approvedBy'])

@if (!is_null($createdBy) && $createdBy->is($approvedBy))
    <footer>
        <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold">
            Prepared & Approved By
        </h1>
        <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
            {{ $createdBy->name }}
        </h1>
        <h2>
            {{ $createdBy->employee->position }}
        </h2>
    </footer>
@else
    <footer class="is-clearfix">
        @if (!is_null($createdBy))
            <aside
                class="is-pulled-left"
                style="width: 50%"
            >
                <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold">
                    Prepared By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $createdBy->name }}
                </h1>
                <h2>
                    {{ $createdBy->employee->position }}
                </h2>
            </aside>
        @endif
        @if (!is_null($approvedBy))
            <aside
                class="is-pulled-right"
                style="width: 50%"
            >
                <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold">
                    Approved By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $approvedBy->name }}
                </h1>
                <h2>
                    {{ $approvedBy->employee->position }}
                </h2>
            </aside>
        @endif
    </footer>
@endif

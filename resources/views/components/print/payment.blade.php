@props(['model'])

<section class="is-clearfix">
    @if (!$model->isPaymentInCredit())
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Payment Type
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $model->payment_type }}
            </h1>
        </aside>
    @endif
    <aside
        class="is-pulled-left"
        style="width: 25% !important"
    >
        <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
            Cash Amount
        </h1>
        <h1 class="has-text-black is-size-6 pr-2">
            {{ number_format($model->payment_in_cash, 2) }}
            ({{ number_format($model->cashReceivedInPercentage, 2) }}%)
        </h1>
    </aside>
    @if ($model->isPaymentInCredit())
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Credit Amount
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ number_format($model->payment_in_credit, 2) }}
                ({{ number_format($model->creditPayableInPercentage, 2) }}%)
            </h1>
        </aside>
    @endif
</section>

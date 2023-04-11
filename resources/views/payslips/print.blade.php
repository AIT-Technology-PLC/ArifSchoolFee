<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <title> {{ $payslip['employee']->user->name }} Payslip - {{ userCompany()->name }} </title>
    <link
        rel="shortcut icon"
        type="image/png"
        href="{{ asset('img/favicon.png') }}"
    />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css"
        integrity="sha256-WLKGWSIJYerRN8tbNGtXWVYnUM5wMJTXD8eG4NtGcDM="
        crossorigin="anonymous"
    >
    <link
        href="{{ asset('css/app.css') }}"
        rel="stylesheet"
    >

    @include('assets.print-css')
</head>

<body class="{{ userCompany()->hasPrintTemplate() ? 'company-background company-y-padding company-x-padding' : 'px-6' }}">
    @if (!userCompany()->hasPrintTemplate())
        <x-print.header />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                EMPLOYEE
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $payslip['employee']->user->name }}
            </h1>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                PAYROLL REF N<u>o</u>
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $payslip['payroll']->code }}
            </h1>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                PAYROLL DAYS
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $payslip['payroll']->working_days ?? userCompany()->working_days }} Days
            </h1>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                PAYROLL PERIOD
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $payslip['payroll']->starting_period->toFormattedDateString() }} <br>
                {{ $payslip['payroll']->ending_period->toFormattedDateString() }}
            </h1>
        </aside>
    </section>

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            Payslip
        </h1>
    </section>

    <section class="is-clearfix mt-5">
        <table
            class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color is-pulled-left"
            style="width: 47.5% !important"
        >
            <thead>
                <tr>
                    <th>Earnings</th>
                    <th style="width: 30% !important">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payslip as $compensation => $amount)
                    @continue($compensations->where('name', $compensation)->isEmpty())
                    <tr>
                        @if ($compensations->whereIn('type', ['earning', 'none'])->where('name', $compensation)->isNotEmpty())
                            <td> {{ $compensation }} </td>
                            <td class="has-text-right"> {{ number_format($amount, 2) }} </td>
                        @endif
                    </tr>
                @endforeach
                <tr>
                    <td class="has-text-weight-bold"> Gross Salary </td>
                    <td class="has-text-right has-text-weight-bold is-underlined"> {{ number_format($payslip['gross_salary'], 2) }} </td>
                </tr>
            </tbody>
        </table>

        <table
            class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color is-pulled-left"
            style="width: 47.5% !important;margin-left: 5% !important"
        >
            <thead>
                <tr>
                    <th>Deductions</th>
                    <th style="width: 30% !important">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payslip as $compensation => $amount)
                    @continue($compensations->where('name', $compensation)->isEmpty())
                    <tr>
                        @if ($compensations->where('type', 'deduction')->where('name', $compensation)->isNotEmpty())
                            <td> {{ $compensation }} </td>
                            <td class="has-text-right"> {{ number_format($amount, 2) }} </td>
                        @endif
                    </tr>
                @endforeach
                <tr>
                    <td> Income Tax </td>
                    <td class="has-text-right"> {{ number_format($payslip['income_tax'], 2) }} </td>
                </tr>
                <tr>
                    <td class="has-text-weight-bold"> Total Deductions </td>
                    <td class="has-text-right has-text-weight-bold is-underlined"> {{ number_format($payslip['deductions'], 2) }} </td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="mt-5 mb-6">
        <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
            <tr>
                <th
                    colspan="2"
                    class="has-text-weight is-uppercase"
                > Summary </th>
            </tr>

            <tr>
                <td>Working Days</td>
                <td
                    style="width: 30% !important"
                    class="has-text-right"
                > {{ number_format($payslip['working_days'], 2) }} Days </td>
            </tr>

            <tr>
                <td>Absence Days</td>
                <td
                    style="width: 30% !important"
                    class="has-text-right"
                > {{ number_format($payslip['absence_days'], 2) }} Days </td>
            </tr>

            @if (isset($payslip['Overtime']))
                <tr>
                    <td>Overtime</td>
                    <td
                        style="width: 30% !important"
                        class="has-text-right"
                    > {{ number_format($payslip['Overtime'], 2) }} </td>
                </tr>
            @endif

            <tr>
                <td> Gross Salary </td>
                <td
                    style="width: 30% !important"
                    class="has-text-right"
                > {{ number_format($payslip['gross_salary'], 2) }} </td>
            </tr>

            <tr>
                <td> Taxable Income </td>
                <td
                    style="width: 30% !important"
                    class="has-text-right"
                > {{ number_format($payslip['taxable_income'], 2) }} </td>
            </tr>

            <tr>
                <td>Total Deductions</td>
                <td
                    style="width: 30% !important"
                    class="has-text-right"
                > {{ number_format($payslip['deductions'], 2) }} </td>
            </tr>

            <tr>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
            </tr>

            <tr>
                <td class="has-text-weight-bold">Net Payable</td>
                <td
                    style="width: 30% !important"
                    class="has-text-right has-text-weight-bold is-underlined"
                > {{ number_format($payslip['net_payable'], 2) }} </td>
            </tr>

            @if (isset($payslip['net_payable_after_absenteeism']) && $payslip['net_payable_after_absenteeism'] != $payslip['net_payable'])
                <tr>
                    <td> &nbsp; </td>
                    <td> &nbsp; </td>
                </tr>
                <tr>
                    <td class="has-text-weight-bold">Net Payable After Absenteesim</td>
                    <td
                        style="width: 30% !important"
                        class="has-text-right has-text-weight-bold is-underlined"
                    > {{ number_format($payslip['net_payable_after_absenteeism'], 2) }} </td>
                </tr>
            @endif
        </table>
    </section>

    <x-print.user
        :created-by="$payslip['payroll']->createdBy ?? null"
        :approved-by="$payslip['payroll']->paidBy ?? null"
    />

    <x-print.footer-marketing />
</body>

</html>

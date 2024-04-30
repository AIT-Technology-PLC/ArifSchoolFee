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
    <title> Payroll #{{ $payroll->code }} - {{ userCompany()->name }} </title>
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

    @include('assets.print-css', ['company' => userCompany()])
</head>

<body class="{{ userCompany()->hasPrintTemplate() ? 'company-background company-y-padding company-x-padding' : 'px-6' }}">
    @if (!userCompany()->hasPrintTemplate())
        <x-print.header />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <section class="py-5">
        <aside class="">
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                REF N<u>o</u>
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $payroll->code }}
            </h1>
        </aside>
        <aside class="mt-3">
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Date
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ now()->toFormattedDateString() }}
            </h1>
        </aside>
        <aside class="mt-3">
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Payroll Period
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $payroll->starting_period->toFormattedDateString() }} - {{ $payroll->ending_period->toFormattedDateString() }}
            </h1>
        </aside>
    </section>

    <section class="mt-5">
        <h1 class="has-text-grey-dark">
            <b>SUBJECT:</b> Credit To Below Account
        </h1>
        <h1 class="has-text-grey-dark">
            <b>TO:</b> {{ userCompany()->payroll_bank_name ?? 'N/A' }}
        </h1>
    </section>

    <section class="mt-5 has-text-justified">
        We <b>{{ userCompany()->name }}</b> request you to credit the below mentioned amounts from our <b>Account No:{{ userCompany()->payroll_bank_account_number ?? 'N/A' }}</b>
        of <b>{{ userCompany()->name }}</b> to be deposited to our Employees below as per their Account Numbers.
        <br>
        Total amount of <b>{{ userCompany()->isBasicSalaryAfterAbsenceDeduction() ? money($employees->sum('net_payable')) : money($employees->sum('net_payable_after_absenteeism')) }}
            ({{ numberToWords(userCompany()->isBasicSalaryAfterAbsenceDeduction() ? $employees->sum('net_payable') : $employees->sum('net_payable_after_absenteeism')) }})</b>.
    </section>

    <section class="table-breaked">
        <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
            <thead>
                <tr class="is-borderless">
                    <td class="is-borderless">&nbsp;</td>
                </tr>
                <tr class="is-borderless">
                    <td class="is-borderless">&nbsp;</td>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Employee Name</th>
                    <th>Amount</th>
                    <th>Account No.</th>
                    <th>Phone No.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                        <td> {{ $employee['employee_name'] }} </td>
                        @if (userCompany()->isBasicSalaryAfterAbsenceDeduction())
                            <td class="has-text-right"> {{ money($employee['net_payable']) }} </td>
                        @else
                            <td class="has-text-right"> {{ money($employee['net_payable_after_absenteeism']) }} </td>
                        @endif

                        <td class="has-text-centered"> {{ $employee['employee']->bank_account }} </td>
                        <td class="has-text-centered"> {{ $employee['employee']->phone }} </td>
                    </tr>
                @endforeach
                <tr>
                    <td
                        class="is-borderless has-text-weight-bold has-text-right is-uppercase"
                        colspan="2"
                    >
                        Total Amount
                    </td>
                    @if (userCompany()->isBasicSalaryAfterAbsenceDeduction())
                        <td class="is-borderless has-text-weight-bold has-text-right">
                            {{ money($employees->sum('net_payable')) }}
                        </td>
                    @else
                        <td class="is-borderless has-text-weight-bold has-text-right">
                            {{ money($employees->sum('net_payable_after_absenteeism')) }}
                        </td>
                    @endif
                    <td
                        class="is-borderless"
                        colspan="2"
                    ></td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="mt-6">
        <x-print.user
            :created-by="null"
            :approved-by="$payroll->approvedBy ?? null"
        />
    </section>

    <x-print.footer-marketing />
</body>

</html>

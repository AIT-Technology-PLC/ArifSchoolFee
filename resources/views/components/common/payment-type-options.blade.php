@props(['selectedPaymentType' => '', 'paymentType' => '' ?? ['Cash Payment', 'Credit Payment', 'Bank Deposit', 'Bank Transfer', 'Deposits', 'Cheque']])

<option
    selected
    disabled
    value=""
>Select Payment</option>
@if ($paymentType == null || in_array('Cash Payment', $paymentType))
    <option
        value="Cash Payment"
        @selected($selectedPaymentType == 'Cash Payment')
    >Cash Payment</option>
@endif

@if ($paymentType == null || in_array('Credit Payment', $paymentType))
    <option
        value="Credit Payment"
        @selected($selectedPaymentType == 'Credit Payment')
    >Credit Payment</option>
@endif

@if ($paymentType == null || in_array('Bank Deposit', $paymentType))
    <option
        value="Bank Deposit"
        @selected($selectedPaymentType == 'Bank Deposit')
    >Bank Deposit</option>
@endif

@if ($paymentType == null || in_array('Bank Transfer', $paymentType))
    <option
        value="Bank Transfer"
        @selected($selectedPaymentType == 'Bank Transfer')
    >Bank Transfer</option>
@endif

@if ($paymentType == null || in_array('Deposits', $paymentType))
    <option
        value="Deposits"
        @selected($selectedPaymentType == 'Deposits')
    >Deposits</option>
@endif

@if ($paymentType == null || in_array('Cheque', $paymentType))
    <option
        value="Cheque"
        @selected($selectedPaymentType == 'Cheque')
    >Cheque</option>
@endif

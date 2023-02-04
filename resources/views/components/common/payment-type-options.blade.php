@props(['selectedPaymentType' => ''])

<option
    selected
    disabled
    value=""
>Select Payment</option>
<option
    value="Cash Payment"
    @selected($selectedPaymentType == 'Cash Payment')
>Cash Payment</option>
<option
    value="Credit Payment"
    @selected($selectedPaymentType == 'Credit Payment')
>Credit Payment</option>
<option
    value="Bank Deposit"
    @selected($selectedPaymentType == 'Bank Deposit')
>Bank Deposit</option>
<option
    value="Bank Transfer"
    @selected($selectedPaymentType == 'Bank Transfer')
>Bank Transfer</option>
<option
    value="Deposits"
    @selected($selectedPaymentType == 'Deposits')
>Deposits</option>
<option
    value="Cheque"
    @selected($selectedPaymentType == 'Cheque')
>Cheque</option>

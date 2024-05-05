<x-mail::message>
@if ($hasSales)
# Sales Report Ready!

Good morning, {{ str($user->name)->ucfirst()->words(1, '')->toString() }}!

Your sales report for <strong>{{ $formattedPeriod }}</strong> is ready. Have a look!

Download the attached PDF document and enjoy.
@else
# No Sales Report!

Good morning, {{ str($user->name)->ucfirst()->words(1, '')->toString() }}!

There were no sales made {{ $period[0]->isSameDay($period[1]) ? 'on' : 'in' }} <strong>{{ $formattedPeriod }}</strong>.
@endif

Thanks,<br>
Onrica PLC
</x-mail::message>

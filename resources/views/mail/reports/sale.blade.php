<x-mail::message>
@if ($hasSales)
# Sales Report Ready!

Good morning, {{ str($user->name)->ucfirst()->words(1, '')->toString() }}!

@if ($period[0]->isSameDay($period[1]))
Your sales report for <strong>{{ $period[0]->toFormattedDateString() }}</strong> is ready. Have a look!
@else
Your sales report for <strong>{{ $period[0]->monthName }}, {{ $period[0]->year }}</strong> is ready. Have a look!
@endif

Download the attached PDF document and enjoy.
@else
# No Sales Report!

Good morning, {{ str($user->name)->ucfirst()->words(1, '')->toString() }}!

@if ($period[0]->isSameDay($period[1]))
There were no sales made on <strong>{{ $period[0]->toFormattedDateString() }}</strong>.
@else
There were no sales made in <strong>{{ $period[0]->monthName }}, {{ $period[0]->year }}</strong>.
@endif

@endif

Thanks,<br>
Onrica PLC
</x-mail::message>

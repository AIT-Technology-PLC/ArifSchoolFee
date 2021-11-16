<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Gdn;
use App\Models\Reservation;
use App\Notifications\ReservationApproved;
use App\Notifications\ReservationCancelled;
use App\Notifications\ReservationConverted;
use App\Notifications\ReservationMade;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    private $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->middleware('isFeatureAccessible:Reservation Management');

        $this->reservationService = $reservationService;
    }

    public function approve(Reservation $reservation, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $reservation);

        if (!auth()->user()->hasWarehousePermission('sales',
            $reservation->reservationDetails->pluck('warehouse_id')->toArray())) {
            return back()->with('failedMessage', 'You do not have permissions to approve in one or more of the warehouses.');
        }

        [$isExecuted, $message] = $action->execute($reservation, ReservationApproved::class, 'Make Reservation');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function reserve(Reservation $reservation)
    {
        $this->authorize('reserve', $reservation);

        [$isExecuted, $message] = $this->reservationService->reserve($reservation);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(notifiables('Approve Reservation', $reservation->createdBy), new ReservationMade($reservation));

        return back();
    }

    public function cancel(Reservation $reservation)
    {
        $this->authorize('cancel', $reservation);

        [$isExecuted, $message] = $this->reservationService->cancel($reservation);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(notifiables('Approve Reservation', $reservation->createdBy), new ReservationCancelled($reservation));

        return back();
    }

    public function convert(Reservation $reservation)
    {
        $this->authorize('convert', $reservation);

        $this->authorize('create', Gdn::class);

        [$isExecuted, $message] = $this->reservationService->convertToGdn($reservation);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(notifiables('Approve GDN', $reservation->createdBy), new ReservationConverted($reservation));

        return back();
    }
}

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
use App\Services\Models\ReservationService;
use App\Utilities\Notifiables;
use Barryvdh\DomPDF\Facade\Pdf;
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

        [$isExecuted, $message] = $action->execute($reservation, ReservationApproved::class, 'Make Reservation');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function reserve(Reservation $reservation)
    {
        $this->authorize('reserve', $reservation);

        [$isExecuted, $message] = $this->reservationService->reserve($reservation, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Reservation', $reservation->reservationDetails->pluck('warehouse_id'), $reservation->createdBy),
            new ReservationMade($reservation)
        );

        return back();
    }

    public function printed(Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        if (!$reservation->isApproved()) {
            return back()->with('failedMessage', 'This Reservation is not approved yet.');
        }

        if ($reservation->isCancelled()) {
            return back()->with('failedMessage', 'This Reservation is cancelled.');
        }

        $reservation->load(['reservationDetails.product', 'customer', 'contact', 'warehouse', 'company', 'createdBy', 'approvedBy']);

        return Pdf::loadView('reservations.print', compact('reservation'))->stream();
    }

    public function cancel(Reservation $reservation)
    {
        $this->authorize('cancel', $reservation);

        [$isExecuted, $message] = $this->reservationService->cancel($reservation);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Reservation', $reservation->reservationDetails->pluck('warehouse_id'), $reservation->createdBy),
            new ReservationCancelled($reservation)
        );

        return back();
    }

    public function convert(Reservation $reservation)
    {
        $this->authorize('convert', $reservation);

        $this->authorize('create', Gdn::class);

        [$isExecuted, $message] = $this->reservationService->convertToGdn($reservation, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byNextActionPermission('Approve GDN', $reservation->createdBy),
            new ReservationConverted($reservation)
        );

        return back();
    }
}
